<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use function Laravel\Prompts\text;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\table;


class UserCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'myroad:user-create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $this->info('Create a new user');

        $name = text(
            label: 'What is your name?',
            placeholder: 'John Doe',
            required: true,
            validate: fn (string $name) => match (true) {
                strlen($name) < 3 => 'Please enter at least 3 characters.',
                default => null,
            },
        );

        $email = text(
            label: 'What is your email?',
            placeholder: 'user@example.com',
            required: true,
            validate: fn (string $email) => match (true) {
                ! filter_var($email, FILTER_VALIDATE_EMAIL) => 'Please enter a valid email address.',
                $this->emailExists($email) => 'This email address is already in use.',
                default => null,
            },
        );

        $role = select(
            label: 'What role should the user '. $email.' have?',
            options: config('myconstants.initial_roles'),
            default: 'admin',
            hint: 'The role may be changed at any time.'
        );

        $confirmed = confirm(
            label: 'Are you sure you want to create the user '. $email.' with '. $role . ' role?',
            default: false,
            yes: 'I accept',
            no: 'I decline',
            hint: 'Please confirm to continue.'
        );

        if($confirmed){

            $password = password(
                label: 'What is your password?',
                validate: fn (string $value) => match (true) {
                    strlen($value) < 8 => 'The password must be at least 8 characters.',
                    default => null
                }
            );

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'roleId' => Role::where('name', $role)->first()->id,
            ]);

            $this->info('User created successfully. Here are the details:');
            table(
                ['Name', 'Email', 'Role'],
                [
                    [$name, $email, $role]
                ],
            );

        }


    }

    private function emailExists(string $email): bool
    {
        return User::where('email', $email)->exists();
    }
}
