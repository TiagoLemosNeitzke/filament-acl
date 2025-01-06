<?php

namespace TiagoLemosNeitzke\FilamentAcl\Resources\RoleResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use TiagoLemosNeitzke\FilamentAcl\Resources\RoleResource;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['label'] = $data['name'];

        return parent::mutateFormDataBeforeCreate($data);
    }
}
