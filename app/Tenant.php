<?php

namespace App;

use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class Tenant
{
    public function __construct(Website $website = null, Hostname $hostname = null, User $admin = null)
    {
        $this->website = $website;
        $this->hostname = $hostname;
        $this->admin = $admin;
    }

    public static function getRootFqdn()
    {
        $rootHostname = Hostname::where('website_id', null)->first();
        if ($rootHostname) {
            \Log::info('Root FQDN found: ' . $rootHostname->fqdn);
            return $rootHostname->fqdn;
        }
        \Log::warning('No root FQDN found.');
        return null;
    }

    public static function delete($name)
    {
        if ($tenant = Hostname::where('fqdn', $name)->firstOrFail()) {
            app(HostnameRepository::class)->delete($tenant, true);
            app(WebsiteRepository::class)->delete($tenant->website, true);
            return "Tenant {$name} successfully deleted.";
        }
    }

    public static function deleteById($id)
    {
        if ($tenant = Hostname::where('id', $id)->firstOrFail()) {
            app(HostnameRepository::class)->delete($tenant, true);
            app(WebsiteRepository::class)->delete($tenant->website, true);
            return "Tenant with id {$id} successfully deleted.";
        }
    }

    public static function deleteByFqdn($fqdn)
    {
        if ($tenant = Hostname::where('fqdn', $fqdn)->firstOrFail()) {
            app(HostnameRepository::class)->delete($tenant, true);
            app(WebsiteRepository::class)->delete($tenant->website, true);
            return "Tenant {$fqdn} successfully deleted.";
        }
    }

    public static function registerTenant($name, $email = null, $password = null): Tenant
    {
        $name = strtolower($name);
        $email = strtolower($email);

        $website = new Website;
        app(WebsiteRepository::class)->create($website);

        $hostname = new Hostname;
        $hostname->fqdn = $name;
        app(HostnameRepository::class)->attach($hostname, $website);

        app(Environment::class)->tenant($hostname->website);

        $migrations = getcwd() . '/database/migrations/';
        $files_to_preserve = glob($migrations . '*.php');

        foreach ($files_to_preserve as $file) {
            rename($file, $file . '.txt');
        }

        \Artisan::call('config:clear');
        \Artisan::call('voyager:install', ['--with-dummy' => true]);

        foreach ($files_to_preserve as $file) {
            rename($file.'.txt', $file);
        }

        $voyager_migrations = getcwd() . '/vendor/tcg/voyager/publishable/database/migrations/*.php';
        $files_to_kill = glob($voyager_migrations);
        $files_to_kill = array_map('basename', $files_to_kill);

        foreach ($files_to_kill as $file) {
            $path = $migrations. '/'. $file;
            unlink($path);
        }

        $admin = null;
        if ($email) {
            $admin = static::makeAdmin($name, $email, $password);
        }

        return new Tenant($website, $hostname, $admin);
    }

    private static function makeAdmin($name, $email, $password): User
    {
        $admin = User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password)]);
        $admin->setRole('admin')->save();

        return $admin;
    }

    public static function tenantExists($name)
    {
        return Hostname::where('fqdn', $name)->exists();
    }

    public static function runCommand($tenantId, $command)
    {
        $tenant = Hostname::findOrFail($tenantId);
        app(Environment::class)->tenant($tenant->website);
        Artisan::call($command);
        return Artisan::output();
    }
}
