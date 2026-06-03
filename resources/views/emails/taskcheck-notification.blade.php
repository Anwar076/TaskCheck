@extends('emails.layouts.taskcheck', [
    'pageTitle' => $title ?? 'TaskCheck',
    'headerTitle' => 'TaskCheck',
    'headerSubtitle' => 'Checklist & kwaliteitscontrole',
    'metaText' => $metaText ?? null,
])

@section('email-body')
    <p style="margin:0 0 12px;font-size:14px;color:#475569;">Beste {{ $greetingName }},</p>
    <h1 class="tc-h1" style="margin:0 0 16px;font-size:22px;line-height:1.3;color:#0f172a;font-weight:700;">{{ $title }}</h1>
    <div style="font-size:15px;line-height:1.7;color:#334155;">
        {!! nl2br(e($bodyText)) !!}
    </div>

    @if(!empty($ctaLabel) && !empty($ctaUrl))
        <table role="presentation" cellspacing="0" cellpadding="0" style="margin-top:24px;">
            <tr>
                <td style="border-radius:10px;background-color:#4f46e5;">
                    <a href="{{ $ctaUrl }}" class="tc-btn" style="display:inline-block;padding:14px 24px;font-size:15px;font-weight:600;color:#ffffff;text-decoration:none;border-radius:10px;">
                        {{ $ctaLabel }}
                    </a>
                </td>
            </tr>
        </table>
    @endif

    @if($showMarketing ?? false)
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:24px;border:1px solid #e0e7ff;border-radius:12px;background-color:#f5f3ff;">
            <tr>
                <td style="padding:16px 18px;">
                    <p style="margin:0 0 8px;font-size:13px;font-weight:700;color:#4338ca;">Waarom teams kiezen voor TaskCheck</p>
                    <p style="margin:0;font-size:13px;line-height:1.6;color:#475569;">
                        Sneller controles uitvoeren, minder fouten op locatie en realtime overzicht voor managers.
                    </p>
                </td>
            </tr>
        </table>
    @endif

    <p style="margin:24px 0 0;font-size:14px;line-height:1.6;color:#334155;">
        Met vriendelijke groet,<br>
        <strong style="color:#0f172a;">Team TaskCheck</strong>
    </p>
@endsection
