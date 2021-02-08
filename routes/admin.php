<?php

Route::module('authors');
Route::module('articles');



Route::name('block_validate')->get('/block_validate', 'BlockValidateController@checkBlocks');
