<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;

beforeEach(function () {
    $this->app->register(\Beartropy\Ui\BeartropyUiServiceProvider::class);
    $this->app->register(\BladeUI\Icons\BladeIconsServiceProvider::class);
    $this->app->register(\BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
});

it('renders a basic input', function () {
    $html = Blade::render('<x-input />');
    expect($html)->toContain('<input');
});

it('renders input with label and placeholder', function () {
    $html = Blade::render('<x-input label="Email" placeholder="Your email" />');
    expect($html)->toContain('Email');
    expect($html)->toContain('placeholder="Your email"');
});

it('renders with magic color and size', function () {
    $html = Blade::render('<x-input red lg />');
    expect($html)->toContain('red');
    expect($html)->toContain('lg');
});

it('renders start and end slot content', function () {
    $html = Blade::render('<x-input>
        <x-slot name="start"><span class="icon-start">S</span></x-slot>
        <x-slot name="end"><span class="icon-end">E</span></x-slot>
    </x-input>');
    expect($html)->toContain('icon-start');
    expect($html)->toContain('icon-end');
});

it('renders clearable button and password toggle', function () {
    $html = Blade::render('<x-input clearable type="password" value="test" />');
    expect($html)->toContain('aria-label="Clear"');
    expect($html)->toContain('Show password');
});

it('renders a basic input with label and placeholder', function () {
    $html = Blade::render('<x-input label="Username" name="username" placeholder="Type your username" />');
    expect($html)->toContain('Username');
    expect($html)->toContain('placeholder="Type your username"');
    expect($html)->toContain('name="username"');
    expect($html)->toContain('<input');
});

it('applies magic props color and size', function () {
    $html = Blade::render('<x-input red lg />');
    expect($html)->toContain('red'); // Color preset aplicado (en alguna clase)
    expect($html)->toContain('lg');  // Size preset aplicado (en alguna clase)
});

it('renders the start slot content', function () {
    $html = Blade::render('<x-input><x-slot name="start"><span class="start-slot">S</span></x-slot></x-input>');
    expect($html)->toContain('start-slot');
});

it('renders the end slot content', function () {
    $html = Blade::render('<x-input><x-slot name="end"><span class="end-slot">E</span></x-slot></x-input>');
    expect($html)->toContain('end-slot');
});

it('renders clearable button when clearable and value present', function () {
    $html = Blade::render('<x-input clearable value="hasvalue" />');
    expect($html)->toContain('aria-label="Clear"');
});

it('renders copy button when copyButton is true', function () {
    $html = Blade::render('<x-input copyButton value="abc123" />');
    expect($html)->toContain('aria-label="Copiar al portapapeles"');
});

it('falls back to default color if color is invalid', function () {
    $html = Blade::render('<x-input blue />');
    expect($html)->toContain('beartropy'); // O el nombre de tu color default en las clases
});

it('renders input with custom iconStart and iconEnd', function () {
    $html = Blade::render('<x-input icon-start="plus" icon-end="user" />');
    expect($html)->toContain('beartropy-inputbase-start-slot');
    expect($html)->toContain('beartropy-inputbase-end-slot');
});

it('renders all features at once', function () {
    $html = Blade::render('
        <x-input 
            label="Multi"
            red
            lg
            type="password"
            value="secreto"
            clearable
            copyButton
            icon-start="plus"
            icon-end="user"
        >
            <x-slot name="start"><span class="my-slot-start">START</span></x-slot>
            <x-slot name="end"><span class="my-slot-end">END</span></x-slot>
        </x-input>
    ');
    expect($html)->toContain('Multi');
    expect($html)->toContain('aria-label="Clear"');
    expect($html)->toContain('Show password');
    expect($html)->toContain('aria-label="Copiar al portapapeles"');
    expect($html)->toContain('my-slot-start');
    expect($html)->toContain('my-slot-end');
    expect($html)->toContain('<svg');
    expect($html)->toContain('red');
    expect($html)->toContain('lg');
});

it('renders error message if error is set', function () {
    $html = Blade::render('<x-input label="Email" name="email" :custom-error="\'Email inválido\'" />');
    expect($html)->toContain('Email inválido');
});

it('matches the expected snapshot for a complex input', function () {
    $html = Blade::render('
        <x-input
            id="complex-input"
            label="Snapshot"
            red
            lg
            type="password"
            value="123456"
            clearable
            copyButton
            icon-start="plus"
            icon-end="user"
        >
            <x-slot name="start"><span class="snap-start">START</span></x-slot>
            <x-slot name="end"><span class="snap-end">END</span></x-slot>
        </x-input>
    ');
    expect($html)->toMatchSnapshot();
});