@extends('templates.app')

@section('title', 'News')

@section('description', 'Latest updates about the service.')

@section('content')
    <h2>News</h2>
    <div style="border: 1px dashed #f00; padding: 10px; background-color: #ff0;">
        <p>
            The Blockland Glass website was hacked in April 2023 and the database was compromised. <strong>Details including usernames, e-mail addresses, passwords, and IP addresses were stolen.</strong>
            Although the passwords were hashed and salted, it was done with a weak hashing algorithm and are therefore still vulnerable to brute force attacks.
        </p>
        <p style="color: #f00;">
            <strong>Users who registered anytime before May 2023 are advised to change their passwords on other websites immediately.</strong>
        </p>
        <p>
            This website has been completely rewritten and no longer stores e-mail addresses or passwords as we are now opting to use Steam OpenID for authentication instead.
            We apologize for the incident and hope that in time we can regain your trust.
        </p>
    </div>
    <h3>2023-05-21: Yes, we're back. No, you can't login yet</h3>
    <p>
        The new website is now running and the in-game Mod Manager should be working again. It doesn't require any client update to facilitate this.
    </p>
    <p>
        We're still aiming to release a new client update soon to fix bugs and remove redundant features such as Glass Live.
    </p>
    <p>
        <strong>There are a few add-ons (approximately ~100 out of 1,476) that have missing files. The download button for them will be grayed out and inaccessible.</strong> We're still figuring out the best way to restore these.
    </p>
    <p>
        <strong>There are also a load of things that are still unfinished/missing such as (but not limited to):</strong>
    </p>
    <ul>
        <li>Logging in.</li>
        <li>Uploading new add-ons.</li>
        <li>Add-on screenshots.</li>
        <li>Commenting on add-ons.</li>
        <li>The add-ons home page (where the overview of trending/recently uploaded add-ons was).</li>
        <li>Searching for add-ons on the website (the in-game Mod Manager searching is working though and so is browsing the <a href="{{ route('addons.boards', [], false) }}">Boards</a> here and in-game).</li>
        <li>The RTB Archive.</li>
    </ul>
    <p>
        Steam authentication is technically "done" but as we have not finished rewriting the Glass add-on review process, nor have we started setting up the BLID linking system, the login button has been hidden as there's no point logging in without those features.
    </p>
    <p>
        As a heads-up on the process, any existing add-ons you had uploaded will automatically be restored to you once you link the BLID that you uploaded the add-ons with. You will be able to link multiple BLIDs.
    </p>
    <p>
        <strong>So, while you wait for us to finish rewriting the site, we're opening it early to at least make Glass add-ons available here and in-game again.</strong> It should also stop the error spam.
    </p>
    <p>
        <em>
            Shock
        </em>
    </p>
    <h3>2023-05-06: An update regarding the April data breach</h3>
    <p>
        Originally posted on Discord:
    </p>
    <p>
        "Just wanted to provide a quick update. I’ve been working through the breach of the Glass server with Conan, Shock, and McTwist. It appears that both a SQL injection exploit and path traversal exploit were used. The former allowed the attacker to manipulate add-on titles and descriptions as many saw, as well as grant themselves the role of admin to access the admin panel of the site. The latter allowed the attack to get access to the Stripe API key and access a small amount of Glass Live chat logs. The claims of the attacker having access to “plain text credit card information” is false, as this information has never been handled by Glass as an intentional security measure.
    </p>
    <p>
        The attacker was unable to escalate privileges on the server itself; they did not gain root access to the server.
    </p>
    <p>
        We’ve worked together to figure out a path forward and put together a list of critical security fixes to get in before bringing the site back online. Generally the changes are straight forward, but extensive, so it may take a bit. Some critical changes being planned are:
    </p>
    <ul>
        <li>A thorough audit of our MySQL interface with a move to an injection-safe query builder</li>
        <li>Moving away from email-based authentication to reduce the amount of sensitive information we handle</li>
        <li>Adopting modern password hashing techniques</li>
        <li>Adopting OAuth login such as Steam Auth</li>
        <li>Better containerization of web content, protecting sensitive keys and files from being exposed from any potential future path traversal exploit</li>
    </ul>
    <p>
        Additionally, we’ll be discontinuing Glass Live. It was developed in a time where Discord hadn’t quite taken off, but we think that it’s mostly redundant these days. This also allows us to move away from some weaker authentication methods that are necessitated by the lack of HTTPS support by TGE. This will likely also mean putting the in-game mod manager in to a “read-only” mode, disallowing comments to be made in-game.
    </p>
    <p>
        Lastly, I’ll be handing off ownership of Glass entirely. This project has gotten farther and grown larger than I had dreamed of when I started it 8+ years ago. I learned a lot from it and I don’t think I’d be where I am in my career today without it. I’m no longer able to contribute to the project due to the terms of my employment, which is a large part of why the project has fallen in to decay over the past few years. Given the extent of changes needed for Glass to come back online, and the attention it requires, it’s no longer feasible for me to continue ownership.
    </p>
    <p>
        I first recall meeting Shock when he started beta testing Glass Live when I began development on it in 2016. He gave valuable feedback early on and continued on to become a moderator, admin, and contributor to the project for years afterward. Shock will be primarily taking over ownership of the domain and server hosting, and I’ve also given McTwist and Conan ownership on GitHub and will be working with all three of them to hand over any remaining privileges and data.
    </p>
    <p>
        Thanks for your patience and understanding."
    </p>
    <p>
        <em>
            Jincux
            <br />
            Project Founder
        </em>
    </p>
    <div class="row center-xs">
        <div class="col-xs">
            <a href="#">Back to the top</a>
        </div>
    </div>
@endsection
