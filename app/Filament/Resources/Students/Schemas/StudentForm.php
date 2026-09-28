<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([

                // 1. Common Academic & Institute Details
                Section::make('Institute & Academic Info')
                    ->schema([

                        Select::make('institute_id')
                            ->relationship('institute', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('class')
                            ->label('Class')
                            ->placeholder('e.g. 10th / MCA / B.Tech')
                            ->disabled(fn (string $operation): bool => $operation === 'edit')
                            ->helperText(fn (string $operation): ?string => $operation === 'edit' ? 'Use the "Change Class" action to update class & division.' : null),

                        TextInput::make('division')
                            ->label('Division')
                            ->placeholder('e.g. A / B / Morning')
                            ->disabled(fn (string $operation): bool => $operation === 'edit'),

                    ])->columns(3),

                // 2. Student List (Repeater for creation: single or multiple students)
                Section::make('Student Details')
                    ->description('Add one or more students below. Each entry will create an individual student record.')
                    ->visible(fn (string $operation): bool => $operation === 'create')
                    ->schema([

                        Repeater::make('students_list')
                            ->label('Students')
                            ->schema([

                                TextInput::make('full_name')
                                    ->label('Full Name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('roll_number')
                                    ->label('Roll Number')
                                    ->maxLength(100),

                                TextInput::make('phone')
                                    ->label('Mobile Number')
                                    ->tel()
                                    ->required()
                                    ->maxLength(20),

                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->maxLength(255),

                            ])
                            ->columns(4)
                            ->defaultItems(1)
                            ->minItems(1)
                            ->addActionLabel('+ Add Another Student')
                            ->reorderable(false)
                            ->collapsible()
                            ->cloneable(),

                    ]),

                // 2b. Individual Student Details (Shown when editing an existing record)
                Section::make('Student Details')
                    ->visible(fn (string $operation): bool => $operation === 'edit')
                    ->schema([

                        TextInput::make('full_name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('roll_number')
                            ->label('Roll Number')
                            ->maxLength(100),

                        TextInput::make('phone')
                            ->label('Mobile Number')
                            ->tel()
                            ->required()
                            ->maxLength(20),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                    ])->columns(2),

                // 3. Assigned Courses (Applied to all created students or edited student)
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

                            ])
                            ->addActionLabel('Add Course')
                            ->collapsible()
                            ->cloneable(),

                    ])

            ]);
    }
}

