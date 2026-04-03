<?php

declare(strict_types=1);

namespace App\Filament\Resources\Teams\Pages;

use App\Filament\Resources\Teams\TeamResource;
use App\Models\Team;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

final class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->saveLogo();
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset($data['logo']);

        return $data;
    }

    private function saveLogo(): void
    {
        /** @var Team $team */
        $team = $this->getRecord();
        $logo = $this->data['logo'] ?? null;

        if (empty($logo)) {
            return;
        }

        $filePath = is_array($logo) ? reset($logo) : $logo;

        if ($filePath) {
            $absolutePath = Storage::disk('public')->path($filePath);
            $team->clearMediaCollection('logo');
            $team->addMedia($absolutePath)->toMediaCollection('logo');
        }
    }
}
