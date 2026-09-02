import { chromium } from 'playwright-core';
import fs from 'node:fs/promises';

const base = 'http://127.0.0.1:8765';
const output = new URL('../artifacts/horeca-feature-screenshots/', import.meta.url).pathname;
await fs.mkdir(output, { recursive: true });

const browser = await chromium.launch({
  executablePath: '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
  headless: true,
});

async function login(page, email) {
  await page.goto(`${base}/login`);
  await page.locator('#email').fill(email);
  await page.locator('#password').fill('password');
  await Promise.all([page.waitForLoadState('networkidle'), page.locator('#email').locator('xpath=ancestor::form').locator('button[type="submit"]').click()]);
}

async function capture(page, path, filename) {
  await page.goto(`${base}${path}`, { waitUntil: 'networkidle' });
  await hideNotificationPrompt(page);
  await page.screenshot({ path: `${output}${filename}`, fullPage: true });
}

async function hideNotificationPrompt(page) {
  await page.evaluate(() => {
    const label = [...document.querySelectorAll('*')].find((element) => element.children.length === 0 && element.textContent?.trim().startsWith('Meldingen'));
    let node = label;
    while (node && (!node.querySelector?.('button') || (node.textContent?.length ?? 0) > 500)) node = node.parentElement;
    if (node) node.style.display = 'none';
  });
}

const context = await browser.newContext({ viewport: { width: 1440, height: 1000 }, deviceScaleFactor: 1 });
const admin = await context.newPage();
await login(admin, 'horeca@taskcheck.test');
await capture(admin, '/admin/lists', '01-digitale-horeca-takenlijsten.png');
await capture(admin, '/admin/dashboard', '03-realtime-voortgang.png');
await capture(admin, '/admin/submissions/1', '04-beoordeling-en-afwijkingen.png');
await capture(admin, '/admin/weekly-overview', '05-weekrapportage-en-inzichten.png');
await capture(admin, '/admin/locations', '06-locatie-en-organisatiebeheer.png');

const employeeContext = await browser.newContext({ viewport: { width: 1440, height: 1000 }, deviceScaleFactor: 1 });
const employee = await employeeContext.newPage();
await login(employee, 'keuken@taskcheck.test');
await employee.goto(`${base}/employee/submissions/4`, { waitUntil: 'networkidle' });
await hideNotificationPrompt(employee);
await employee.getByText('Temperatuur levering', { exact: true })
  .locator('xpath=ancestor::*[.//button][1]').locator('button').last().click({ timeout: 2000 }).catch(() => {});
await employee.waitForTimeout(300);
await employee.screenshot({ path: `${output}02-bewijs-en-haccp-controle.png`, fullPage: true });

await browser.close();
console.log(output);
