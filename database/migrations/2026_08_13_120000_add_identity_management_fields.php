<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('entra_enabled')->default(false);
            $table->boolean('entra_sso_required')->default(false);
            $table->boolean('entra_mfa_required')->default(false);
            $table->string('entra_tenant_id')->nullable();
            $table->string('entra_client_id')->nullable();
            $table->text('entra_client_secret')->nullable();
            $table->json('entra_admin_group_ids')->nullable();
            $table->json('entra_employee_group_ids')->nullable();
            $table->uuid('scim_endpoint_key')->nullable()->unique();
            $table->string('scim_token_hash', 64)->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('entra_object_id')->nullable()->index();
            $table->string('entra_tenant_id')->nullable()->index();
            $table->string('scim_external_id')->nullable()->index();
            $table->timestamp('last_sso_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn([
            'entra_object_id', 'entra_tenant_id', 'scim_external_id', 'last_sso_at',
        ]));
        Schema::table('companies', fn (Blueprint $table) => $table->dropColumn([
            'entra_enabled', 'entra_sso_required', 'entra_mfa_required', 'entra_tenant_id',
            'entra_client_id', 'entra_client_secret', 'entra_admin_group_ids',
            'entra_employee_group_ids', 'scim_endpoint_key', 'scim_token_hash',
        ]));
    }
};
