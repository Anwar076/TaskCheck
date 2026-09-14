from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parents[2]
logo = Image.open(ROOT / "public/logos/taskcheck-logo.png").convert("RGBA")
# Badge in SVG: x=24 y=32 w=96 h=96
badge = logo.crop((24, 32, 120, 128)).convert("RGBA")


def fit_center(src, size, bg=(37, 99, 235, 255), pad_ratio=0.12):
    canvas = Image.new("RGBA", (size, size), bg)
    inner = int(size * (1 - 2 * pad_ratio))
    icon = src.resize((inner, inner), Image.Resampling.LANCZOS)
    offset = ((size - inner) // 2, (size - inner) // 2)
    canvas.alpha_composite(icon, offset)
    return canvas


def fit_center_transparent(src, size, pad_ratio=0.18):
    canvas = Image.new("RGBA", (size, size), (0, 0, 0, 0))
    inner = int(size * (1 - 2 * pad_ratio))
    icon = src.resize((inner, inner), Image.Resampling.LANCZOS)
    offset = ((size - inner) // 2, (size - inner) // 2)
    canvas.alpha_composite(icon, offset)
    return canvas


def to_rgb(img, bg=(37, 99, 235)):
    out = Image.new("RGB", img.size, bg)
    out.paste(img, mask=img.split()[-1])
    return out


# iOS App Icon 1024
ios_icon_path = ROOT / "mobile-app/ios/App/App/Assets.xcassets/AppIcon.appiconset/AppIcon-512@2x.png"
ios_icon = fit_center(badge, 1024, bg=(37, 99, 235, 255), pad_ratio=0.08)
to_rgb(ios_icon, (37, 99, 235)).save(ios_icon_path, "PNG")
print("iOS AppIcon", ios_icon_path)


def make_splash(size=(2732, 2732), logo_width=980):
    bg = Image.new("RGB", size, (238, 243, 249))
    lw = logo_width
    ratio = logo.height / logo.width
    lh = int(lw * ratio)
    scaled = logo.resize((lw, lh), Image.Resampling.LANCZOS)
    x = (size[0] - lw) // 2
    y = (size[1] - lh) // 2 - 40
    layer = Image.new("RGBA", size, (0, 0, 0, 0))
    layer.paste(scaled, (x, y), scaled)
    out = Image.alpha_composite(bg.convert("RGBA"), layer)
    return out.convert("RGB")


splash = make_splash()
splash_dir = ROOT / "mobile-app/ios/App/App/Assets.xcassets/Splash.imageset"
for name in ["splash-2732x2732.png", "splash-2732x2732-1.png", "splash-2732x2732-2.png"]:
    splash.save(splash_dir / name, "PNG")
    print("iOS splash", name)

android_res = ROOT / "mobile-app/android/app/src/main/res"
legacy = {
    "mipmap-mdpi": 48,
    "mipmap-hdpi": 72,
    "mipmap-xhdpi": 96,
    "mipmap-xxhdpi": 144,
    "mipmap-xxxhdpi": 192,
}
foreground = {
    "mipmap-mdpi": 108,
    "mipmap-hdpi": 162,
    "mipmap-xhdpi": 216,
    "mipmap-xxhdpi": 324,
    "mipmap-xxxhdpi": 432,
}

for folder, size in legacy.items():
    icon = fit_center(badge, size, bg=(37, 99, 235, 255), pad_ratio=0.08)
    rgb = to_rgb(icon, (37, 99, 235))
    (android_res / folder).mkdir(parents=True, exist_ok=True)
    rgb.save(android_res / folder / "ic_launcher.png", "PNG")
    rgb.save(android_res / folder / "ic_launcher_round.png", "PNG")
    print("Android launcher", folder, size)

for folder, size in foreground.items():
    fg = fit_center_transparent(badge, size, pad_ratio=0.18)
    fg.save(android_res / folder / "ic_launcher_foreground.png", "PNG")
    print("Android foreground", folder, size)

bg_xml = android_res / "values/ic_launcher_background.xml"
bg_xml.write_text(
    '<?xml version="1.0" encoding="utf-8"?>\n'
    "<resources>\n"
    "    <color name=\"ic_launcher_background\">#2563EB</color>\n"
    "</resources>\n",
    encoding="utf-8",
)
print("Updated ic_launcher_background")

splash_targets = {
    "drawable/splash.png": (480, 800),
    "drawable-port-mdpi/splash.png": (320, 480),
    "drawable-port-hdpi/splash.png": (480, 800),
    "drawable-port-xhdpi/splash.png": (720, 1280),
    "drawable-port-xxhdpi/splash.png": (1080, 1920),
    "drawable-port-xxxhdpi/splash.png": (1440, 2560),
    "drawable-land-mdpi/splash.png": (480, 320),
    "drawable-land-hdpi/splash.png": (800, 480),
    "drawable-land-xhdpi/splash.png": (1280, 720),
    "drawable-land-xxhdpi/splash.png": (1920, 1080),
    "drawable-land-xxxhdpi/splash.png": (2560, 1440),
}

for rel, (w, h) in splash_targets.items():
    canvas = Image.new("RGB", (w, h), (238, 243, 249))
    if w >= 600:
        ratio = logo.height / logo.width
        lw = min(int(min(w, h) * 0.72), int(w * 0.7))
        lh = int(lw * ratio)
        if lh > int(h * 0.35):
            lh = int(h * 0.35)
            lw = int(lh / ratio)
        scaled = logo.resize((lw, lh), Image.Resampling.LANCZOS)
        x = (w - lw) // 2
        y = (h - lh) // 2
        layer = Image.new("RGBA", (w, h), (0, 0, 0, 0))
        layer.paste(scaled, (x, y), scaled)
        out = Image.alpha_composite(canvas.convert("RGBA"), layer).convert("RGB")
    else:
        side = int(min(w, h) * 0.34)
        icon = badge.resize((side, side), Image.Resampling.LANCZOS)
        x = (w - side) // 2
        y = (h - side) // 2
        layer = Image.new("RGBA", (w, h), (0, 0, 0, 0))
        layer.paste(icon, (x, y), icon)
        out = Image.alpha_composite(canvas.convert("RGBA"), layer).convert("RGB")
    path = android_res / rel
    path.parent.mkdir(parents=True, exist_ok=True)
    out.save(path, "PNG")
    print("Android splash", rel, w, h)

print("DONE")
