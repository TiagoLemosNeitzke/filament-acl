<?php

namespace TiagoLemosNeitzke\FilamentAcl\Resources\RoleResource\Pages;

use TiagoLemosNeitzke\FilamentAcl\Resources\RoleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['label'] = $data['name'];
        return parent::mutateFormDataBeforeCreate($data);
    }

}
