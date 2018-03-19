## Setup

Use [these steps](./docker/README.md) to run our environment in docker.


## Branching Model

We use the branching model found here: http://nvie.com/posts/a-successful-git-branching-model/

1. Always branch new features off the `develop` branch `git checkout -b [111-feature-branch-name]`
  - Note: 111 should be the issue number you're working on
1. Commit your changes to the feature branch
  - Always include the issue number at the begining of the commit message
  - e.g. `git commit -m '#111 made some changes'`
1. Push your feature branch to this repo
1. Create a pull request from your feature branch to the develop branch
  - The title of your pull request should match the title of the task you're completing
  - Include a link to the issue ticket in the description
  - Include a GIF of your changes. Use licecap if you need software.
  - If your changes are difficult to include into one GIF, make two.
  - Provide a summary of the work you completed in the description of the pull request.
  - Add steps to QA so the reviewer can quickly know what changed and why
1. Review the "diff" of your code
  - Make sure all changes are needed and wanted.
  - Comment to explain any unusual code.
  - Clean up code.
1. Your changes will be merged after QA

## Making Style Changes
1. All custom styles (scss) are found on the VM in `/vagrant/public/css/source/`
1. SSH into the VM and navigate to the scss files. `vagrant ssh` then `cd /vagrant/public/css/source/`
1. Use `compass watch --poll` to watch for changes to the scss files

## Testing
1. Create a database called "bestline_testing"
1. Run tests before each pull request
1. Navigate into the VM `vagrant ssh`
1. Navigate to the vagrant folder `cd /vagrant`
1. Start phpunit `vendor/phpunit/phpunit/phpunit`
1. Expect to see "OK" or a list of errors

## Laravel PHP Framework

Documentation for the entire framework can be found on the [Laravel website](http://laravel.com/docs). The application uses version 4.

### License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
