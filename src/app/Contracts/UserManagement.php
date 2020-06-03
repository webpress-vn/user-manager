<?php

namespace VCComponent\Laravel\User\Contracts;

interface UserManagement
{
		public function ableToShow($id);

		public function ableToCreate();

    public function ableToUpdate();

    public function ableToUpdateProfile($id);
    
    public function ableToDelete($id);
}
