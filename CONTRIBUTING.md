# Contributing

## Guidelines

- This project follows the [PSR-2 coding style](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md).
- Ensure your editor supports `.editorconfig` (check https://editorconfig.org if you don't know).

## Setting up the development environment

This project uses [Laravel Sail](https://laravel.com/docs/10.x/sail) to provide a consistent development environment. It also uses Laravel Octane & RoadRunner as the application server in both development and production.

You will need [Docker](https://www.docker.com/) installed, [WSL 2](https://learn.microsoft.com/en-us/windows/wsl/install) if you are on Windows and approximately **2GB** of free storage for Sail.

**The following steps must be done within Linux.** Sail is not supported on Windows, which is one of the reasons why WSL is required.

1. Clone the repo.
2. Start Sail using `./vendor/bin/sail up` (or `./vendor/bin/sail up -d` to run in detached mode).
3. Connect to the Sail container using `./vendor/bin/sail shell`.
4. Do `./vendor/bin/rr get-binary` to fetch the RoadRunner binary for Laravel Octane.
5. `exit` the Sail container.
6. Stop Sail using `./vendor/bin/sail down`.
7. Add Execute permissions for the newly downloaded RoadRunner binary using `chmod +x ./rr`.
8. Rebuild the Sail image using `./vendor/bin/sail build --no-cache`.
9. Start Sail again (as written in Step 2).

From this point, you just start and stop Sail using the aforementioned commands. Steps 3 - 8 only need to be done once. 

To view a full list of Sail commands, just do `./vendor/bin/sail`

You can also make an alias for `./vendor/bin/sail` -> `sail` by adding the following to your `.bashrc`:

```bash
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

If you have issues setting up Laravel Sail, please visit https://laravel.com/docs/10.x/sail
