{*
11192 - Cleaned PHP notifications
NATS-610 recaptchav2
*}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{$config.NICE_NAME}</title>
    <!--link rel="stylesheet" type="text/css" href="nats_builder.css?skinid={$_skinid}" /-->
    {* Inlcude JS File with all files movded to footer *}
    <!--script type="text/javascript" src="jscript/aff_all.js"></script-->
    <!--script type="text/javascript" src="jscript/jquery.main.js"></script-->
    <link rel="icon" type="image/x-icon" href="https://sellapleasure.com/img-smp/favicon/favicon.ico">
    <!--meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"-->

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />


    <meta name="description" content="SellAPleasure - get your sales to climax">
    <meta charset="utf-8">
    <meta name="author" content="">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <!--meta name="viewport" content="width=device-width, initial-scale=1.0" /-->


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!--link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400&display=swap" rel="stylesheet"-->

    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400&display=swap" type="text/css" as="style" onload="this.onload = null; this.rel ='stylesheet';" />

    <!--
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>





    <script type="text/javascript" src="jscript/jquery.main.js"></script>

    {* Setup Javascript necessary for this page *}
    {literal}
    <script>
        //start the jquery on loads
        //$(document).ready(function(){

        //$(".lang_flags").tooltip({
        //offset: [-15, 50],
        //predelay: 10, 
        //delay: 0,
        //tipClass: 'small-tooltip',
        //layout: '<div><div class="tooltip-arrow-border"></div><div class="tooltip-arrow"></div></div>'
        //}).dynamic();

        //});
    </script>
    {/literal}



    {include file="nats:include_analytics-doc-head"}
    {literal}
    <!-- critical css -->
    <style>
        @charset "UTF-8";

        @font-face {
            font-family: Poppins;
            font-style: normal;
            font-weight: 200;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/poppins/v20/pxiByp8kv8JHgFVrLFj_V1s.ttf) format('truetype')
        }

        @font-face {
            font-family: Poppins;
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: url(https://fonts.gstatic.com/s/poppins/v20/pxiEyp8kv8JHgFVrFJA.ttf) format('truetype')
        }

        @font-face {
            font-family: Poppins;
            font-style: normal;
            font-weight: 700;
            font-display: swap;
            src: url('https://sellapleasure.com/css-smp/poppins/Poppins-Bold.eot');
            src: local(''), url('https://sellapleasure.com/css-smp/poppins/Poppins-Bold.eot?#iefix') format('embedded-opentype'), url('https://sellapleasure.com/css-smp/poppins/Poppins-Bold.woff2') format('woff2'), url('https://sellapleasure.com/css-smp/poppins/Poppins-Bold.woff') format('woff'), url('https://sellapleasure.com/css-smp/poppins/Poppins-Bold.ttf') format('truetype'), url('https://sellapleasure.com/css-smp/poppins/Poppins-Boldsvg#Poppins') format('svg')
        }

        @font-face {
            font-family: Poppins;
            font-style: normal;
            font-weight: 200;
            font-display: swap;
            src: url('https://sellapleasure.com/css-smp/poppins/Poppins-ExtraLight.eot');
            src: local(''), url('https://sellapleasure.com/css-smp/poppins/Poppins-ExtraLight.eot?#iefix') format('embedded-opentype'), url('https://sellapleasure.com/css-smp/poppins/Poppins-ExtraLight.woff2') format('woff2'), url('https://sellapleasure.com/css-smp/poppins/Poppins-ExtraLight.woff') format('woff'), url('https://sellapleasure.com/css-smp/poppins/Poppins-ExtraLight.ttf') format('truetype'), url('https://sellapleasure.com/css-smp/poppins/Poppins-ExtraLightsvg#Poppins') format('svg')
        }

        @font-face {
            font-family: poppins;
            font-style: normal;
            font-weight: 700;
            font-display: swap;
            src: url('https://sellapleasure.com/css-smp/poppins/Poppins-Bold.eot');
            src: local(''), url('https://sellapleasure.com/css-smp/poppins/Poppins-Bold.eot?#iefix') format('embedded-opentype'), url('https://sellapleasure.com/css-smp/poppins/Poppins-Bold.woff2') format('woff2'), url('https://sellapleasure.com/css-smp/poppins/Poppins-Bold.woff') format('woff'), url('https://sellapleasure.com/css-smp/poppins/Poppins-Bold.ttf') format('truetype'), url('https://sellapleasure.com/css-smp/poppins/Poppins-Boldsvg#Poppins') format('svg')
        }

        @font-face {
            font-family: poppins;
            font-style: normal;
            font-weight: 200;
            font-display: swap;
            src: url('https://sellapleasure.com/css-smp/poppins/Poppins-ExtraLight.eot');
            src: local(''), url('https://sellapleasure.com/css-smp/poppins/Poppins-ExtraLight.eot?#iefix') format('embedded-opentype'), url('https://sellapleasure.com/css-smp/poppins/Poppins-ExtraLight.woff2') format('woff2'), url('https://sellapleasure.com/css-smp/poppins/Poppins-ExtraLight.woff') format('woff'), url('https://sellapleasure.com/css-smp/poppins/Poppins-ExtraLight.ttf') format('truetype'), url('https://sellapleasure.com/css-smp/poppins/Poppins-ExtraLightsvg#Poppins') format('svg')
        }

        .fadeInUpShort {
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            -webkit-transform: translateZ(0);
            transform: translateZ(0)
        }

        [type=submit],
        button {
            -webkit-appearance: button
        }

        @media (min-width:992px) {
            .col-lg-5 {
                flex: 0 0 auto;
                width: 41.66666667%
            }
        }

        @media only screen and (max-width:767px) {
            .nav-logo-wrap .logo {
                max-width: 150px
            }

            .nav-logo-wrap .logo {
                width: 100%;
                max-width: 250px
            }
        }

        .home-section {
            padding: 10px 50px
        }

        :root {
            --bs-blue: #0d6efd;
            --bs-indigo: #6610f2;
            --bs-purple: #6f42c1;
            --bs-pink: #d63384;
            --bs-red: #dc3545;
            --bs-orange: #fd7e14;
            --bs-yellow: #ffc107;
            --bs-green: #198754;
            --bs-teal: #20c997;
            --bs-cyan: #0dcaf0;
            --bs-white: #fff;
            --bs-gray: #6c757d;
            --bs-gray-dark: #343a40;
            --bs-gray-100: #f8f9fa;
            --bs-gray-200: #e9ecef;
            --bs-gray-300: #dee2e6;
            --bs-gray-400: #ced4da;
            --bs-gray-500: #adb5bd;
            --bs-gray-600: #6c757d;
            --bs-gray-700: #495057;
            --bs-gray-800: #343a40;
            --bs-gray-900: #212529;
            --bs-primary: #0d6efd;
            --bs-secondary: #6c757d;
            --bs-success: #198754;
            --bs-info: #0dcaf0;
            --bs-warning: #ffc107;
            --bs-danger: #dc3545;
            --bs-light: #f8f9fa;
            --bs-dark: #212529;
            --bs-primary-rgb: 13, 110, 253;
            --bs-secondary-rgb: 108, 117, 125;
            --bs-success-rgb: 25, 135, 84;
            --bs-info-rgb: 13, 202, 240;
            --bs-warning-rgb: 255, 193, 7;
            --bs-danger-rgb: 220, 53, 69;
            --bs-light-rgb: 248, 249, 250;
            --bs-dark-rgb: 33, 37, 41;
            --bs-white-rgb: 255, 255, 255;
            --bs-black-rgb: 0, 0, 0;
            --bs-body-color-rgb: 33, 37, 41;
            --bs-body-bg-rgb: 255, 255, 255;
            --bs-font-sans-serif: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            --bs-font-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            --bs-gradient: linear-gradient(180deg, rgba(255, 255, 255, 0.15), rgba(255, 255, 255, 0));
            --bs-body-font-family: var(--bs-font-sans-serif);
            --bs-body-font-size: 1rem;
            --bs-body-font-weight: 400;
            --bs-body-line-height: 1.5;
            --bs-body-color: #212529;
            --bs-body-bg: #fff
        }

        *,
        ::after,
        ::before {
            box-sizing: border-box
        }

        @media (prefers-reduced-motion:no-preference) {
            :root {
                scroll-behavior: smooth
            }
        }

        body {
            margin: 0;
            font-family: var(--bs-body-font-family);
            font-size: var(--bs-body-font-size);
            font-weight: var(--bs-body-font-weight);
            line-height: var(--bs-body-line-height);
            color: var(--bs-body-color);
            text-align: var(--bs-body-text-align);
            background-color: var(--bs-body-bg);
            -webkit-text-size-adjust: 100%
        }

        h1,
        h2 {
            margin-top: 0;
            margin-bottom: .5rem;
            font-weight: 500;
            line-height: 1.2
        }

        h1 {
            font-size: calc(1.375rem + 1.5vw)
        }

        @media (min-width:1200px) {
            h1 {
                font-size: 2.5rem
            }
        }

        h2 {
            font-size: calc(1.325rem + .9vw)
        }

        @media (min-width:1200px) {
            h2 {
                font-size: 2rem
            }
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem
        }

        ul {
            padding-left: 2rem
        }

        ul {
            margin-top: 0;
            margin-bottom: 1rem
        }

        b {
            font-weight: bolder
        }

        a {
            color: #0d6efd;
            text-decoration: underline
        }

        img {
            vertical-align: middle
        }

        table {
            caption-side: bottom;
            border-collapse: collapse
        }

        tbody,
        td,
        tr {
            border-color: inherit;
            border-style: solid;
            border-width: 0
        }

        button {
            border-radius: 0
        }

        button,
        input {
            margin: 0;
            font-family: inherit;
            font-size: inherit;
            line-height: inherit
        }

        button {
            text-transform: none
        }

        [type=button],
        [type=submit],
        button {
            -webkit-appearance: button
        }

        ::-moz-focus-inner {
            padding: 0;
            border-style: none
        }

        ::-webkit-datetime-edit-day-field,
        ::-webkit-datetime-edit-fields-wrapper,
        ::-webkit-datetime-edit-hour-field,
        ::-webkit-datetime-edit-minute,
        ::-webkit-datetime-edit-month-field,
        ::-webkit-datetime-edit-text,
        ::-webkit-datetime-edit-year-field {
            padding: 0
        }

        ::-webkit-inner-spin-button {
            height: auto
        }

        ::-webkit-search-decoration {
            -webkit-appearance: none
        }

        ::-webkit-color-swatch-wrapper {
            padding: 0
        }

        ::-webkit-file-upload-button {
            font: inherit
        }

        ::file-selector-button {
            font: inherit
        }

        ::-webkit-file-upload-button {
            font: inherit;
            -webkit-appearance: button
        }

        iframe {
            border: 0
        }

        .container-fluid {
            width: 100%;
            padding-right: var(--bs-gutter-x, .75rem);
            padding-left: var(--bs-gutter-x, .75rem);
            margin-right: auto;
            margin-left: auto
        }

        .row {
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            display: flex;
            flex-wrap: wrap;
            margin-top: calc(-1 * var(--bs-gutter-y));
            margin-right: calc(-.5 * var(--bs-gutter-x));
            margin-left: calc(-.5 * var(--bs-gutter-x))
        }

        .row>* {
            flex-shrink: 0;
            width: 100%;
            max-width: 100%;
            padding-right: calc(var(--bs-gutter-x) * .5);
            padding-left: calc(var(--bs-gutter-x) * .5);
            margin-top: var(--bs-gutter-y)
        }

        @media (min-width:992px) {
            .col-lg-5 {
                flex: 0 0 auto;
                width: 41.66666667%
            }

            .col-lg-6 {
                flex: 0 0 auto;
                width: 50%
            }
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            background-color: transparent;
            border: 1px solid transparent;
            padding: .375rem .75rem;
            font-size: 1rem;
            border-radius: .25rem
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1055;
            display: none;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
            overflow-y: auto;
            outline: 0
        }

        .modal-content {
            position: relative;
            display: flex;
            flex-direction: column;
            width: 100%;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, .2);
            border-radius: .3rem;
            outline: 0
        }

        .clearfix::after {
            display: block;
            clear: both;
            content: ""
        }

        .d-flex {
            display: flex !important
        }

        .justify-content-center {
            justify-content: center !important
        }

        .align-items-center {
            align-items: center !important
        }

        .align-self-center {
            align-self: center !important
        }

        .pt-5 {
            padding-top: 3rem !important
        }

        .pb-5 {
            padding-bottom: 3rem !important
        }

        @media (min-width:768px) {
            .mx-md-1 {
                margin-right: .25rem !important;
                margin-left: .25rem !important
            }
        }

        :root {
            scroll-behavior: auto;
            --font-global: HK_Grotesk, arial, sans-serif;
            --font-global-alt: HK_Grotesk_alt, arial, sans-serif
        }

        body,
        html {
            height: 100%;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility
        }

        iframe {
            border: none
        }

        img:not([draggable]) {
            max-width: 100%;
            height: auto
        }

        .relative {
            position: relative
        }

        .stick-fixed {
            position: fixed !important;
            top: 0;
            left: 0
        }

        .clearlist,
        .clearlist li {
            list-style: none;
            padding: 0;
            margin: 0;
            background: 0 0
        }

        .full-wrapper {
            margin: 0 2%
        }

        .page-loader {
            display: block;
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background: #fefefe;
            z-index: 100000
        }

        .loader {
            width: 50px;
            height: 50px;
            position: absolute;
            top: 50%;
            left: 50%;
            margin: -25px 0 0 -25px;
            font-size: 10px;
            text-indent: -12345px;
            border-top: 1px solid rgba(0, 0, 0, .15);
            border-right: 1px solid rgba(0, 0, 0, .15);
            border-bottom: 1px solid rgba(0, 0, 0, .15);
            border-left: 1px solid rgba(0, 0, 0, .55);
            -webkit-border-radius: 50%;
            -moz-border-radius: 50%;
            border-radius: 50%;
            -webkit-animation: .7s linear infinite spinner;
            -moz-animation: .7s linear infinite spinner;
            -ms-animation: spinner 700ms infinite linear;
            -o-animation: .7s linear infinite spinner;
            animation: .7s linear infinite spinner;
            will-change: transform;
            z-index: 100001
        }

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0);
                -moz-transform: rotate(0);
                transform: rotate(0)
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                transform: rotate(360deg)
            }
        }

        @-moz-keyframes spinner {
            0% {
                -webkit-transform: rotate(0);
                -moz-transform: rotate(0);
                transform: rotate(0)
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                transform: rotate(360deg)
            }
        }

        @keyframes spinner {
            0% {
                -webkit-transform: rotate(0);
                -moz-transform: rotate(0);
                transform: rotate(0)
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                transform: rotate(360deg)
            }
        }

        body {
            color: #111;
            font-family: var(--font-global);
            font-size: 18px;
            font-weight: 400;
            letter-spacing: -.00208em;
            line-height: 1.61
        }

        a {
            color: #111;
            text-decoration: underline
        }

        b {
            font-weight: 600
        }

        h1,
        h2 {
            margin-bottom: 1em;
            font-weight: 600;
            line-height: 1.2
        }

        h1 {
            margin-bottom: .5em;
            font-size: 3.25rem;
            letter-spacing: -.04em
        }

        h2 {
            margin-bottom: .5em;
            font-size: 2.875rem;
            letter-spacing: -.04em
        }

        p {
            margin: 0 0 1.5em
        }

        ul {
            margin: 0 0 1.5em
        }

        .uppercase {
            text-transform: uppercase;
            letter-spacing: .0454545em
        }

        .home-section {
            width: 100%;
            display: block;
            position: relative;
            overflow: hidden;
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center center;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover
        }

        @-webkit-keyframes scroll-down-anim {
            0% {
                -webkit-transform: translateY(-3px)
            }

            50% {
                -webkit-transform: translateY(3px)
            }

            100% {
                -webkit-transform: translateY(-3px)
            }
        }

        @keyframes scroll-down-anim {
            0% {
                transform: translateY(-3px)
            }

            50% {
                transform: translateY(3px)
            }

            100% {
                transform: translateY(-3px)
            }
        }

        .scroll-down {
            display: block;
            width: 100%;
            height: 100%;
            text-decoration: none;
            opacity: .9;
            -webkit-animation: 1.15s infinite scroll-down-anim;
            animation: 1.15s infinite scroll-down-anim
        }

        .scroll-down:before {
            display: block;
            content: "";
            width: 33px;
            height: 33px;
            margin: -17px 0 0 -17px;
            position: absolute;
            top: 50%;
            left: 50%;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 3px 5px 0 rgba(0, 0, 0, .1);
            z-index: 1
        }

        .scroll-down-icon {
            display: block;
            width: 13px;
            height: 9px;
            margin-left: -7px;
            margin-top: -3px;
            position: absolute;
            left: 50%;
            top: 50%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="9px"><path fill-rule="evenodd" fill="rgb(17, 17, 17)" d="M13.004,1.626 L6.780,9.003 L6.304,8.474 L6.133,8.664 L-0.004,1.955 L1.453,0.335 L6.550,5.905 L11.536,-0.005 L13.004,1.626 Z"/></svg>');
            background-repeat: no-repeat;
            z-index: 2
        }

        .typewrite .wrap:after {
            -webkit-animation: .7s infinite blink;
            -moz-animation: .7s infinite blink;
            animation: .7s infinite blink
        }

        @-webkit-keyframes blink {
            0% {
                opacity: 1
            }

            50% {
                opacity: 0
            }

            100% {
                opacity: 1
            }
        }

        @-moz-keyframes blink {
            0% {
                opacity: 1
            }

            50% {
                opacity: 0
            }

            100% {
                opacity: 1
            }
        }

        @keyframes blink {
            0% {
                opacity: 1
            }

            50% {
                opacity: 0
            }

            100% {
                opacity: 1
            }
        }

        .wow,
        .wow-menubar {
            opacity: .01
        }

        html:not(.mobile) .wow,
        html:not(.mobile) .wow-menubar {
            will-change: opacity, transform
        }

        .appear-animate .wow.animated {
            opacity: 1;
            -webkit-transform: scale(1);
            transform: scale(1)
        }

        @media (prefers-reduced-motion:reduce),
        print {

            .wow,
            .wow-menubar {
                opacity: 1 !important;
                -webkit-transform: none !important;
                transform: none !important;
                -webkit-animation: none !important;
                animation: none !important
            }
        }

        .fadeInUpShort,
        .fadeScaleIn {
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            -webkit-transform: translateZ(0);
            transform: translateZ(0)
        }

        @keyframes fadeInUpShort {
            0% {
                opacity: 0;
                -webkit-transform: translate3d(0, 37px, 0);
                transform: translate3d(0, 37px, 0)
            }

            to {
                opacity: 1;
                -webkit-transform: translate3d(0, 0, 0);
                transform: translate3d(0, 0, 0)
            }
        }

        .fadeInUpShort {
            -webkit-animation-name: fadeInUpShort;
            animation-name: fadeInUpShort
        }

        @keyframes fadeScaleIn {
            0% {
                opacity: 0;
                -webkit-transform: scale(.975) rotate(.1deg);
                transform: scale(.975) rotate(.1deg)
            }

            to {
                opacity: 1;
                -webkit-transform: scale(1.001) rotate(0);
                transform: scale(1) rotate(0)
            }
        }

        .fadeScaleIn {
            -webkit-animation-name: fadeScaleIn;
            animation-name: fadeScaleIn
        }

        .nav-logo-wrap {
            float: left;
            margin-right: 20px
        }

        .nav-logo-wrap .logo {
            display: flex;
            align-items: center;
            max-width: 188px;
            height: 85px
        }

        .nav-logo-wrap .logo img {
            width: auto;
            max-height: 100%
        }

        .nav-logo-wrap .logo:after,
        .nav-logo-wrap .logo:before {
            display: none
        }

        .logo {
            font-size: 18px;
            font-weight: 600 !important;
            text-decoration: none;
            color: rgba(0, 0, 0, .9)
        }

        .main-nav {
            width: 100%;
            height: 85px !important;
            position: relative;
            top: 0;
            left: 0;
            text-align: left;
            background: rgba(255, 255, 255, 0);
            box-shadow: 0 3px 15px 0 rgba(0, 0, 0, .05);
            z-index: 1030
        }

        .inner-nav {
            display: inline-block;
            position: relative;
            float: right;
            margin: 0 50px 0 0
        }

        .inner-nav ul {
            float: right;
            margin: auto;
            font-size: 18px;
            font-weight: 400;
            text-align: center;
            letter-spacing: 0;
            line-height: 1.3
        }

        .inner-nav ul li {
            float: left;
            margin-left: 35px;
            position: relative
        }

        .inner-nav ul li a {
            color: #555;
            display: inline-block;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            text-decoration: none
        }

        .mobile-nav {
            display: none;
            float: right;
            padding-left: 15px;
            padding-right: 15px;
            vertical-align: middle;
            font-size: 11px;
            font-weight: 400;
            text-transform: uppercase
        }

        .mobile-nav .fa {
            height: 100%;
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            font-size: 24px
        }

        .main-nav.transparent {
            background: 0 0 !important;
            box-shadow: none
        }

        .skip-to-content {
            position: absolute;
            top: 3px;
            left: 3px;
            padding: 20px 40px;
            color: #fff;
            background: #111;
            -webkit-transform: translateY(-150%);
            -moz-transform: translateY(-150%);
            transform: translateY(-150%);
            z-index: 100000
        }

        @media only screen and (max-width:1366px) {
            .full-wrapper {
                margin-left: 30px;
                margin-right: 30px
            }

            .inner-nav ul li {
                margin-left: 32px
            }
        }

        @media only screen and (max-width:1200px) {
            .inner-nav ul {
                font-size: 16px
            }

            .inner-nav ul li {
                margin-left: 23px
            }
        }

        @media only screen and (max-width:1024px) {

            .main-nav,
            .mobile-nav,
            .nav-logo-wrap .logo {
                height: 40px !important;
                line-height: 70px !important
            }

            .home-section {
                background-attachment: scroll
            }
        }

        @media only screen and (max-width:480px) {
            .full-wrapper {
                margin-left: 20px;
                margin-right: 20px
            }
        }

        @media only screen and (max-height:374px) {
            .min-height-100vh {
                min-height: 374px
            }
        }

        @media only screen and (min-width:1024px) and (max-height:1366px) and (-webkit-min-device-pixel-ratio:1.5) {
            html:not(.no-touch) .home-section {
                background-attachment: scroll
            }
        }

        @media all and (-ms-high-contrast:none) {
            .min-height-100vh {
                height: 100vh
            }
        }

        :root {
            --animate-duration: 1s;
            --animate-delay: 1s;
            --animate-repeat: 1
        }

        .animated {
            -webkit-animation-duration: 1s;
            animation-duration: 1s;
            -webkit-animation-duration: var(--animate-duration);
            animation-duration: var(--animate-duration);
            -webkit-animation-fill-mode: both;
            animation-fill-mode: both
        }

        @media (prefers-reduced-motion:reduce),
        print {
            .animated {
                -webkit-animation-duration: 1ms !important;
                animation-duration: 1ms !important;
                -webkit-animation-iteration-count: 1 !important;
                animation-iteration-count: 1 !important
            }
        }

        a.LoginGetHelp:visited {
            color: #e7ca64;
            text-decoration: none
        }

        body {
            margin: 0;
            padding: 0;
            color: #efefef;
            min-width: 100vw !important;
            font-family: Poppins, "Comic Sans MS", "Comic Sans", cursive, -apple-system, BlinkMacSystemFont, avenir next, avenir, segoe ui, helvetica neue, helvetica, Cantarell, Ubuntu, roboto, noto, arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background: #252525;
            font-size: 14px;
            font-weight: 200;
            line-height: 1.5;
            background: url(https://sellapleasure.com/img-smp/sella-bg-r2.jpg) !important;
            background-repeat: no-repeat;
            background-size: cover !important;
            background-attachment: local;
            letter-spacing: .75px;
            -moz-font-feature-settings: "kern" 1;
            -ms-font-feature-settings: "kern" 1;
            -o-font-feature-settings: "kern" 1;
            -webkit-font-feature-settings: "kern" 1;
            font-feature-settings: "kern" 1;
            font-kerning: normal
        }

        img {
            border-style: none
        }

        a {
            text-decoration: none;
            color: #e7ca64;
            font-weight: 500
        }

        h2 {
            text-transform: uppercase;
            color: #ccc;
            font-weight: 200;
            font-family: Poppins, -apple-system, BlinkMacSystemFont, avenir next, avenir, segoe ui, helvetica neue, helvetica, Cantarell, Ubuntu, roboto, noto, arial, sans-serif;
            margin: 0;
            font-size: 14px;
            line-height: 1.22;
            color: #fffeec
        }

        .full-wrapper {
            margin: 0
        }

        .nav-logo-wrap {
            float: left;
            padding: 0 0 0 20px;
            margin-right: 20px
        }

        [type=submit],
        button {
            -webkit-appearance: button;
            background: #e7b606;
            color: #333;
            font-weight: 400
        }

        .min-height-100vh {
            min-height: 100vh;
            min-height: calc(var(--vh, 1vh) * 100)
        }

        .home-section {
            padding: 0;
            margin: 0
        }

        .inner-nav ul li a {
            color: #fff;
            display: inline-block;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            text-decoration: none
        }

        .page {
            padding-top: 90px
        }

        .scroll-down {
            display: block;
            width: 100%;
            height: 100%;
            text-decoration: none;
            opacity: .9;
            -webkit-animation: 1.15s infinite scroll-down-anim;
            animation: 1.15s infinite scroll-down-anim;
            margin: -10px 0 0
        }

        .home-section .scroll-down {
            margin: -110px 0 0
        }

        @media only screen and (min-width:1041px) {
            .nav-logo-wrap .logo img {
                width: auto;
                max-height: 100%;
                min-width: 400px;
                max-width: 400px
            }
        }

        @media only screen and (max-width:1040px) {
            .nav-logo-wrap .logo img {
                width: auto;
                max-height: 100%;
                max-width: 65vw
            }

            .nav-logo-wrap .logo img {
                display: flex;
                align-items: center;
                width: 100%;
                max-width: 100%;
                height: 100%
            }

            .logo img {
                display: flex;
                align-items: center;
                padding: 0 0 5px;
                min-width: 65vw !important
            }
        }

        @media only screen and (max-width:767px) {
            .nav-logo-wrap .logo {
                max-width: 150px
            }

            .nav-logo-wrap .logo {
                width: 100%;
                max-width: 250px
            }
        }

        #main {
            margin: 0 auto;
            width: 100%;
            padding: 0
        }

        #loginBtn {
            background: 0 0;
            color: #fff;
            border: 0 solid transparent;
            display: block;
            text-align: left;
            height: 82px;
            line-height: 82px
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 500;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            max-width: 100vw;
            overflow: auto;
            background-color: #000;
            background-color: rgba(0, 0, 0, .4)
        }

        .close {
            color: #fff;
            float: right;
            font-size: 28px;
            font-weight: 700;
            position: absolute;
            right: -10px;
            top: -10px
        }

        .forgotpwcollapsible {
            background-color: transparent;
            color: #fff;
            padding: 2px;
            width: 100%;
            border: none;
            text-align: right;
            outline: 0;
            font-size: 12px;
            padding: 0 24px 0 0
        }

        .forgotpwcontent {
            padding: 0;
            text-align: center;
            background-color: rgba(0, 0, 0, .25);
            max-height: 0;
            overflow: hidden;
            -webkit-border-radius: 15px;
            -moz-border-radius: 15px;
            border-radius: 15px;
            margin: 2px 0 0
        }

        .SellaLoginLabel {
            display: inline-block;
            min-width: 25%;
            padding: 5px;
            text-align: right;
            font-weight: 600
        }

        .SellaLoginField {
            display: inline-block;
            min-width: 70%;
            padding: 5px;
            text-align: left;
            font-weight: 600
        }

        .login-box {
            color: #efefef;
            font-weight: 600;
            font-size: 14px;
            margin: 0;
            background: rgba(102, 0, 255, .85);
            padding: 10px;
            -webkit-border-radius: 15px;
            -moz-border-radius: 15px;
            border-radius: 15px
        }

        .login-box input[type=password],
        .login-box input[type=text] {
            font-size: 12px;
            font-size: max(13px, 1em);
            font-family: inherit;
            width: 100% !important;
            padding: .25em .5em;
            background-color: rgba(255, 255, 255, .85);
            border: 2px solid var(--input-border);
            margin: 2px;
            font-weight: 700;
            color: #333;
            border-radius: 4px
        }

        .input-entry {
            padding-bottom: 2px;
            color: #efefef;
            text-transform: capitalize;
            font-size: 1em;
            font-weight: 700
        }

        .input-entry .input-value input {
            padding: 2px;
            width: 100%
        }

        a.LoginGetHelp {
            color: #e7b606;
            text-decoration: none
        }

        a.LoginGetHelp:visited {
            color: #e7b606;
            text-decoration: none
        }

        @media only screen and (max-width:768px) {
            .home-section {
                padding: 10px 20px
            }
        }

        @media only screen and (max-width:1024px) {

            .main-nav,
            .mobile-nav,
            .nav-logo-wrap .logo {
                height: 60px !important;
                line-height: 70px !important
            }

            .home-section {
                background-attachment: scroll;
                width: 100vw;
                max-width: 96vw
            }

            .home-section {
                padding: 10px 50px
            }

            .modal-content {
                background-color: rgba(102, 0, 255, 0);
                margin: 2% auto;
                padding: 250px 0 0;
                border: 0 solid #888;
                width: 94%;
                position: relative
            }
        }

        @media only screen and (min-width:1024px) {
            .modal-content {
                background-color: rgba(102, 0, 255, 0);
                margin: 15% auto;
                padding: 20px;
                border: 0 solid #888;
                width: 40%;
                position: relative
            }
        }

        .OpeningCopy,
        .OpeningCopy h2 {
            /*font-size:20px;*/
            text-transform: none;
            font-weight: 200
        }

        .titleopener {
            font-weight: 200;
            clear: both;
            text-transform: capitalize;
        }

        .SellAPleasureSignupBtn {
            display: block;
            margin: 0 auto;
            filter: drop-shadow(-5px 11px 15px #b113aaa6);
            max-width: 175px !important
        }

        .SignupBtnNav {
            margin: 0;
            -webkit-filter: drop-shadow(-5px 19px 11px 7px #ff6600);
            filter: drop-shadow(-5px 11px 15px #b113aaa6)
        }

        .SellAPleasureSignupBtn {
            max-width: 125px
        }

        .SellAPleasureSignupBtn {
            max-width: 105px
        }

        img.SignupBtnNav {
            max-width: 90px
        }

        @media only screen and (max-width:1024px) {
            .home-section {
                padding: 10px 50px
            }
        }
    </style>

    <!-- critical css calls -->

    <link rel="preload" href="https://sellapleasure.com/misc-smp/rhythm/css/bootstrap.min.css" type="text/css" as="style" onload="this.onload=null; this.rel='stylesheet'; " />

    <link rel="preload" href="https://sellapleasure.com/css-smp/rhythm-style-shrunk.css" type="text/css" as="style" onload="this.onload=null; this.rel='stylesheet';" />

    <link rel="preload" href="https://sellapleasure.com/css-smp/style-responsive-sella.css" type="text/css" as="style" onload="this.onload=null; this.rel='stylesheet';" />

    <link rel="preload" href="https://sellapleasure.com/misc-smp/rhythm/css/animate.min.css" type="text/css" as="style" onload="this.onload=null; this.rel='stylesheet';" />

    <link rel="preload" href="https://sellapleasure.com/misc-smp/rhythm/css/splitting.css" type="text/css" as="style" onload="this.onload=null; this.rel='stylesheet';" />


    <link rel="preload" href="https://sellapleasure.com/css-smp/sell-shared-ext-internal.css" type="text/css" as="style" onload="this.onload=null; this.rel='stylesheet';" />

    <link rel="preload" href="https://sellapleasure.com/css-smp/stats.css" type="text/css" as="style" onload="this.onload=null; this.rel='stylesheet';" />

    <link rel="preload" href="https://sellapleasure.com/css-smp/sell-overrides-external.css" type="text/css" as="style" onload="this.onload=null; this.rel='stylesheet';" />


    <!-- CSS -->
    <!--link rel="stylesheet" href="https://sellapleasure.com/misc-smp/rhythm/css/bootstrap.min.css"-->
    <!--link rel="stylesheet" type="text/css" href="https://sellapleasure.com/css-smp/rhythm-style-shrunk.css"-->
    <!--link rel="stylesheet" href="https://sellapleasure.com/css-smp/style-responsive-sella.css"-->
    <!--link rel="stylesheet" href="https://sellapleasure.com/misc-smp/rhythm/css/animate.min.css"-->
    <!--link rel="stylesheet" href="https://sellapleasure.com/misc-smp/rhythm/css/splitting.css"-->
    <!--link rel="stylesheet" type="text/css" href="https://sellapleasure.com/css-smp/sell-shared-ext-internal.css"-->

    <!--link rel="stylesheet" type="text/css" href="https://sellapleasure.com/css-smp/stats.css" /-->
    <!--link rel="stylesheet" href="https://sellapleasure.com/css-smp/sell-overrides-external.css"-->
    <!-- !Styles -->


    <!-- already moved over css --->
    <!--link rel="stylesheet" href="https://sellapleasure.com/misc-smp/rhythm/css/vertical-rhythm.min.css"-->
    <!--link rel="stylesheet" type="text/css" href="https://sellapleasure.com/css-smp/bootstrapDec22R3.min.css"-->
    <!--link rel="stylesheet" href="https://sellapleasure.com/misc-smp/rhythm/css/style.css"-->
    <!--link rel="stylesheet" href="https://sellapleasure.com/misc-smp/rhythm/css/owl.carousel.css"-->
    <!--link rel="stylesheet" type="text/css" href="https://sellapleasure.com/css-smp/alllogin.css" /-->
    <!--link rel="stylesheet" type="text/css" href="https://sellapleasure.com/css-smp/sellapleasure.css" /-->
    <!--link rel="stylesheet" type="text/css" href="https://sellapleasure.com/css-smp/news-floater.css" /-->

    {/literal}

    {if $config.GOOGLE_RECAPTCHA < $smarty.session.g_captcha}<script src="https://www.google.com/recaptcha/api.js" async defer>
        </script>{/if}
        <!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="css/ie.css" /><![endif]-->
        {if empty($smarty.request.page)}
        {assign var="page" value="index"}
        {else}
        {assign var="page" value=$smarty.request.page}
        {/if}
        {use_language_file section=$page}
        {include file="nats:include_google_analytics"}


</head>

<body class="appear-animate SellAPleasure">
    {include file="nats:include_analytics-doc-body-tagmanager"}
    <!-- Page Loader -->
    <div class="page-loader">
        <div class="loader">Loading...</div>

    </div>
    <!-- End Page Loader -->

    <!-- Skip to Content -->
    <a href="#main" class="btn skip-to-content">Skip to Content</a>
    <!-- End Skip to Content -->

    <!-- Page Wrap -->
    <div class="page" id="top">

        <!-- Navigation panel -->
        <nav class="main-nav transparent stick-fixed wow-menubar">
            <div class="full-wrapper relative clearfix">

                <!-- Logo  -->
                <div class="nav-logo-wrap">
                    <a href="https://sellapleasure.com" class="logo">
                        <img src="https://sellapleasure.com/img-smp/sellapleasure-ani-logoRdec2022.svg" alt="Sell A Pleasure" width="400" height="46" class="logo-white" />

                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="mobile-nav" role="button" tabindex="0">
                    <i class="fa fa-bars"></i>
                    <span class="sr-only">Menu</span>
                </div>

                <!-- Main Menu -->
                <div class="inner-nav desktop-nav">
                    <ul class="clearlist">
                        <li class="active"><a href="https://sellapleasure.com/external.php?page=news">FAQ</a></li>
                        <li>
                            <!-- Trigger/Open The Modal -->
                            <button id="loginBtn">Log In</button>
                        </li>
                        <li>
                            <a href="https://sellapleasure.com/external.php?page=signup"><img src='https://sellapleasure.com/img-smp/Button_SignUp.png' class="SignupBtnNav"></a>
                        </li>
                    </ul>
                </div>
                <!-- End Main Menu -->

            </div>
        </nav>
        <!-- End Navigation panel -->


        <main id="main">