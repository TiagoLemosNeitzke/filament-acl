<?php

namespace TiagoLemosNeitzke\FilamentAcl\Resources;

use TiagoLemosNeitzke\FilamentAcl\Models\Permission;
use TiagoLemosNeitzke\FilamentAcl\Models\Role;
use TiagoLemosNeitzke\FilamentAcl\Resources\RoleResource\Pages;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static ?string $modelLabel = 'Regras de acesso';
    protected static ?string $pluralModelLabel = 'Regras de acesso';
    protected static ?string $navigationGroup = 'Configurações do sistema';
    protected static ?string $slug = 'regras-de-acesso';

    public static function form(Form $form): Form
    {
        $permissionsByModel = self::getPermissionsGroupedByModel();
        $schema = [

            Forms\Components\TextInput::make('name')
                ->label('Nome')
                ->required()
                ->maxLength(255),
        ];

        foreach ($permissionsByModel as $model => $permissions) {
            $model = ucfirst(strtolower($model));
            $schema[] = Section::make($model)
                ->schema([
                    CheckboxList::make('permissions')
                        ->label('')
                        ->relationship('permissions', 'label')
                        ->options($permissions)
                        ->columns(4)
                        ->bulkToggleable(),
                ]);
        }

        return $form->schema($schema);
    }

    protected static function getPermissionsGroupedByModel(): array
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode('_', $permission->name);
            $model = $parts[0];
            return $model;
        });

        $groupedPermissions = [];

        foreach ($permissions as $model => $perms) {
            $groupedPermissions[$model] = $perms->pluck('label', 'id')->toArray();
        }

        return $groupedPermissions;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('label')
                    ->label('Label')
                    ->badge()
                    ->color('success')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
