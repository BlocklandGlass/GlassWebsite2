@extends('templates.app')

@section('title', 'Home')

@section('content')
    <div style="text-align: center;">
        <img src="{{ asset('img/logo.webp') }}" title="Blockland Glass" alt="The Blockland Glass logo in color" style="max-width: 100%;" />
        <h2 style="color: #333; font-style: italic;">An open-source Blockland content service</h2>
        <a href="{{ route('addons.addon', ['id' => 11], false) }}" class="btn blue">Download</a><a href="{{ route('addons', [], false) }}" class="btn yellow">Add-Ons</a>
    </div>
    <div>
        <h3>A New Beginning</h3>
        <p>
            Welcome to the new Blockland Glass website. If you haven't heard about the data breach that occurred in April 2023, <a href="{{ route('news', [], false) }}">please click here for further details</a>.
        </p>
        <p>
            This website has been rewritten from scratch to follow modern security practices and now uses a proper web framework called Laravel.
        </p>
        <p>
            We apologize for the distress that the data breach has caused and know there is nothing we can do to make up for it other than to keep working on restoring most of the service back to normal.
        </p>
    </div>
    <div>
        <h3>What's Glass?</h3>
        <p>
            Blockland Glass is a service made for <a href="https://blockland.us" target="_blank" rel="noreferrer">Blockland</a> to help expand and cultivate the community. Glass acts as a content platform offering the ability to download Glass add-ons in-game and manage your server's preferences.
        </p>
    </div>
    <div style="float: right;">
        <img src="{{ asset('img/mod_manager.webp') }}" title="The Glass Mod Manager" alt="A screenshot of the Glass Mod Manager" style="margin: 10px; max-width: 100%;" />
    </div>
    <div>
        <h3>Mod Manager</h3>
        <p>
            The Mod Manager allows you to browse, search, and install add-ons without ever exiting Blockland. You're able to access all add-ons upload directly to Glass, as well as search and download add-ons from the RTB 4 Archive. The Mod Manager also ensures that all of your add-ons are kept up to date via <tt>Support_Updater</tt> and imports your old RTB add-ons to be updated to the latest version.
        </p>
    </div>
    <div>
        <h3>Preferences</h3>
        <p>
            We've implemented our own preferences system to make up for the loss of RTB preferences. All RTB preferences are automatically imported and available to control, along with some new preference types and options in particular.
        </p>
    </div>
    <div>
        <h3>Server Features</h3>
        <p>
            Glass enables you to preview servers before you join them, displaying the server's preview image and player list. On top of that, it allows you to mark your favorite servers, giving you notifications about the server's status and allowing you to view and join it from the main menu. Glass also allows servers to have their own custom loading screen images, similar to how map images worked before version 21.
        </p>
    </div>
    <div>
        <h3>Getting Involved</h3>
        <p>
            Blockland Glass is an open-source project open to any contributions. If you're interested, please help us out on <a href="https://github.com/BlocklandGlass" target="_blank" rel="noreferrer">GitHub</a>.
        </p>
    </div>
    <div class="row center-xs">
        <div class="col-xs">
            <a href="#">Back to the top</a>
        </div>
    </div>
@endsection
