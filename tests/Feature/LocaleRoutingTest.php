<?php

test('root redirects to french home', function () {
    $this->get('/')
        ->assertRedirect('/fr/accueil');
});

test('french locale root redirects to accueil', function () {
    $this->get('/fr/')
        ->assertRedirect('/fr/accueil');
});

test('english locale root redirects to home', function () {
    $this->get('/en/')
        ->assertRedirect('/en/home');
});

test('french home page responds successfully', function () {
    $this->get('/fr/accueil')
        ->assertOk();
});

test('english home page responds successfully', function () {
    $this->get('/en/home')
        ->assertOk();
});

test('unsupported locale returns not found', function () {
    $this->get('/de/accueil')
        ->assertNotFound();
});
