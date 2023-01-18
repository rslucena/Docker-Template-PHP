<?php

namespace Root\Application\Controllers;

use Root\Application\Providers\ValidatorProviders;
use Root\Application\Repositories\UserRepository;

class UserController extends UserRepository
{

    /**
     * Controller function example
     * @param array $Props
     */
    public function createExempleFunction( array $Props ){

        $this->create( $Props );

    }

}