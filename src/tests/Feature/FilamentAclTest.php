<?php

it('will not use debugging functions')
    ->expect(['dd', 'dump', 'ray', 'ds'])
    ->each->not->toBeUsed();
