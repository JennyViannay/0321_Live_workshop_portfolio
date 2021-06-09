<?php

namespace App\Controller\Admin;

use App\Entity\AboutMe;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AboutMeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AboutMe::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
