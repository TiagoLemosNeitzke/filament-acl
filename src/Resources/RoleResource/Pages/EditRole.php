<?php

namespace TiagoLemosNeitzke\FilamentAcl\Resources\RoleResource\Pages;

use TiagoLemosNeitzke\FilamentAcl\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
