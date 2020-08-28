@extends('layout.base')

@section('title', '- About')

@section('content')
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">About</h2>
                <div class="card-text">
                    <p>
                        This site is made by <a href="https://twitter.com/Gory_moon">@Gory_Moon</a> as a hobby project and because a previous site that had this functionality was removed.<br>
                        If you want to donate to help run this site you can do it here <a href="https://paypal.me/GustafJ"><fa style="font-size: x-large" :icon="['fab','cc-paypal']"></fa></a>, it's not needed as I use this for other personal stuff but it's much appreciated.
                    </p>
                    <p>
                        I will continue to add features and work on this as time goes, if you have suggestions you can post a issue on the github below.
                    </p>
                    <h4>Site info</h4>
                    <h6>Version: @version('simple') (build <a href="https://github.com/GoryMoon/SubscriptionWhitelist/commit/@version('commit')">@version('commit')</a>)</h6>
                    <h6>Source code:</h6>
                    <ul>
                        <li>
                            Main: <a href="https://github.com/GoryMoon/SubscriptionWhitelist"><fa style="font-size: x-large" :icon="['fab','github']"></fa> SubscriberWhitelist</a>
                        </li>
                        <li>
                            API: <a href="https://github.com/GoryMoon/SubscriptionWhitelistApi"><fa style="font-size: x-large" :icon="['fab','github']"></fa> SubscriberWhitelistAPI</a>
                        </li>
                    </ul>
                    <h4>Libraries</h4>
                    <h5>PHP</h5>
                    <ul>
                        <li>
                            <a href="https://laravel.com/">Laravel</a>: Main page framework
                        </li>
                        <li>
                            <a href="https://lumen.laravel.com/">Lumen</a>: Api page framework
                        </li>
                        <li>
                            <a href="https://horizon.laravel.com/">laravel/horizon</a>: Dashboard and code-driven configuration for Laravel queues
                        </li>
                        <li>
                            <a href="https://github.com/laravel/telescope">laravel/telescope</a>: An elegant debug assistant for the Laravel framework.
                        </li>
                        <li>
                            <a href="https://github.com/guzzle/guzzle">guzzle/guzzle</a>: Guzzle is a PHP HTTP client library
                        </li>
                        <li>
                            <a href="https://github.com/invisnik/laravel-steam-auth">invisnik/laravel-steam-auth</a>: Laravel Steam Auth
                        </li>
                        <li>
                            <a href="https://github.com/antonioribeiro/version">pragmarx/version</a>: Take control over your Laravel app version
                        </li>
                        <li>
                            <a href="https://github.com/pusher/pusher-http-php">pusher/pusher-php-server</a>: Library for interacting with the Pusher REST API
                        </li>
                        <li>
                            <a href="https://github.com/romanzipp/Laravel-Twitch">romanzipp/laravel-twitch</a>: Twitch PHP Wrapper for Laravel
                        </li>
                        <li>
                            <a href="https://github.com/tightenco/ziggy">tightenco/ziggy</a>: Use your Laravel Named Routes inside JavaScript
                        </li>
                        <li>
                            <a href="https://github.com/Torann/laravel-geoip">torann/geoip</a>: Support for multiple GeoIP services. (Used for cookie displaying)
                        </li>
                        <li>
                            <a href="https://github.com/vinkla/laravel-hashids">vinkla/hashids</a>: A Hashids bridge for Laravel
                        </li>
                    </ul>
                    <h5>JavaScript</h5>
                    <ul>
                        <li>
                            <a href="https://github.com/JeffreyWay/laravel-mix">laravel-mix</a>: An elegant wrapper around Webpack for the 80% use case.
                        </li>
                        <li>
                            <a href="https://github.com/sass/dart-sass">sass</a>: The reference implementation of Sass, written in Dart.
                        </li>
                        <li>
                            <a href="https://github.com/vuejs/vue">vue</a>: Reactive, component-oriented view layer for modern web interfaces.
                        </li>
                        <li>
                            <a href="https://github.com/axios/axios">axios</a>: Promise based HTTP client for the browser and node.js
                        </li>
                        <li>
                            <a href="https://github.com/twbs/bootstrap">bootstrap</a>: Sleek, intuitive, and powerful front-end framework for faster and easier web development.
                        </li>
                        <li>
                            <a href="https://github.com/bootstrap-vue/bootstrap-vue">bootstrap-vue</a>: Bootstrap components for Vue
                        </li>
                        <li>
                            Fontawesome: The iconic font, CSS, and SVG framework
                            <a href="https://github.com/FortAwesome/Font-Awesome">
                                <ul>
                                    <li>
                                        @fortawesome/fontawesome-svg-core
                                    </li>
                                    <li>
                                        @fortawesome/free-brands-svg-icons
                                    </li>
                                    <li>
                                        @fortawesome/free-solid-svg-icons
                                    </li>
                                </ul>
                            </a>
                        </li>
                        <li>
                            <a href="https://github.com/FortAwesome/vue-fontawesome">@fortawesome/vue-fontawesome</a>: Official Vue component for Font Awesome 5
                        </li>
                        <li>
                            <a href="https://github.com/gitbrent/bootstrap4-toggle">bootstrap4-toggle</a>: Bootstrap 4 Toggle is a bootstrap 4 plugin that converts checkboxes into toggles.
                        </li>
                        <li>
                            <a href="https://github.com/laravel/echo">laravel-echo</a>: Laravel Echo library for beautiful Pusher and Socket.IO integration
                        </li>
                        <li>
                            <a href="https://github.com/lodash/lodash">lodash</a>: Lodash modular utilities.
                        </li>
                        <li>
                            <a href="https://github.com/moment/moment">moment</a>: Parse, validate, manipulate, and display dates
                        </li>
                        <li>
                            <a href="https://github.com/FezVrasta/popper.js">popper.js</a>: A kickass library to manage your poppers
                        </li>
                        <li>
                            <a href="https://github.com/pusher/pusher-js">pusher-js</a>: Pusher JavaScript library for browsers, React Native, NodeJS and web workers
                        </li>
                        <li>
                            <a href="https://github.com/bbonnin/vue-morris">vue-morris</a>: Vue.js components wrapping <a href="https://morrisjs.github.io/morris.js/">Morris.js</a> lib
                        </li>
                        <li>
                            <a href="https://github.com/ratiw/vuetable-2">vuetable-2</a>: Datatable component for Vue 2.x
                        </li>
                    </ul>
                </div>
            </div>
        </div>

@endsection
