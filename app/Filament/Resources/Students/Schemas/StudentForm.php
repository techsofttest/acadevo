<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Schemas\Schema;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;

use Filament\Schemas\Components\Section;


class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                

                 Section::make('Student Details')
                    ->schema([

                        TextInput::make('full_name')
                            ->required()
                            ->maxLength(255),

                        Select::make('institute_id')
                            ->relationship('institute', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('division')
                            ->label('Division'),

                        TextInput::make('roll_number'),

                        TextInput::make('email')
                            ->email(),

                        TextInput::make('phone'),

                    ]),

                Section::make('Courses')
                    ->schema([

                        Repeater::make('studentCourses')
                            ->relationship()
                            ->schema([

                                Select::make('course_id')
                                    ->relationship('course', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                DatePicker::make('start_date')
                                    ->required(),

                                DatePicker::make('end_date'),


                            ])
                            ->addActionLabel('Add Course')
                            ->collapsible()
                            ->cloneable(),

                    ])


            ]);
    }
}
