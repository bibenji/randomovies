<?php

namespace Randomovies\Controller\EasyAdmin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Randomovies\Entity\User;

class AdminController extends BaseAdminController
{
	public function persistEntity($entity)
    {
		if ($entity instanceof User) {
			$entity->setPassword($this->encryptPlainPassword($entity));			
		}
        parent::persistEntity($entity);
    }
	
	public function updateEntity($entity)
	{
		if ($entity instanceof User) {
			$entity->setPassword($this->encryptPlainPassword($entity));			
		}
		parent::updateEntity($entity);
	}
	
	private function encryptPlainPassword(User $user)
	{
		return $this->get('security.password_encoder')->encodePassword($user, $user->getPlainPassword());		
	}
	
	public function createAction()
	{
	    $suggestionId = $this->request->query->get('id');
	    
	    return $this->redirectToRoute('admin_movie_new_with_suggestion', [
            'id' => $suggestionId,	            
	    ]);
	}
}
