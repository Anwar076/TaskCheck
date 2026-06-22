<?php

namespace App\Console\Commands;

use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use Illuminate\Console\Command;

class SetCompanyTypeCommand extends Command
{
    protected $signature = 'company:set-type
                            {company : Company ID or admin e-mailadres}
                            {type : cleaning, horeca of other}';

    protected $description = 'Stel het bedrijfstype in voor een organisatie';

    public function handle(): int
    {
        $identifier = (string) $this->argument('company');
        $type = (string) $this->argument('type');

        if (! in_array($type, ['cleaning', 'horeca', 'other'], true)) {
            $this->error('Type moet cleaning, horeca of other zijn.');

            return self::FAILURE;
        }

        $company = is_numeric($identifier)
            ? Company::find($identifier)
            : User::where('email', $identifier)->first()?->company;

        if (! $company) {
            $this->error('Geen organisatie gevonden voor: '.$identifier);

            return self::FAILURE;
        }

        $company->update(['company_type' => $type]);

        $this->info("Bedrijfstype van \"{$company->name}\" (ID {$company->id}) ingesteld op: {$type}");

        return self::SUCCESS;
    }
}
