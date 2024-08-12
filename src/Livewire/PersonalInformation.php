<?php

namespace Xbigdaddyx\Companion\Livewire;

use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class PersonalInformation extends MyProfileComponent
{
    protected string $view = "companion::livewire.personal-information";
    public array $only = ['name', 'email', 'phone', 'department', 'avatar_url', 'employee_id'];
    public array $data;
    public $user;
    public $userClass;

    // this example shows an additional field we want to capture and save on the user
    public function mount()
    {
        $this->user = Filament::getCurrentPanel()->auth()->user();
        $this->userClass = get_class($this->user);

        $this->form->fill($this->user->only($this->only));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        FileUpload::make('avatar_url')
                            ->avatar(),
                        TextInput::make('name')
                            ->columnSpanFull()
                            ->required(),
                        TextInput::make('email')
                            ->required(),
                        TextInput::make('phone'),
                        TextInput::make('department'),
                        TextInput::make('employee_id')->label('Employee ID'),
                    ]),

            ])
            ->statePath('data');
    }

    // only capture the custome component field
    public function submit(): void
    {
        $data = collect($this->form->getState())->only($this->only)->all();
        $this->user->update($data);
        Notification::make()
            ->success()
            ->title(__('Personal information updated successfully'))
            ->send();
    }
}


// public function render()
// {
//     return view('accuracy::livewire.search-carton');
// }
// use Livewire\Component;

// class PersonalInformation extends Component
// {
//     public function render()
//     {
//         return view('livewire.personal-information');
//     }
// }
