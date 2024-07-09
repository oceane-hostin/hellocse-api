<?php
 namespace App\Enums;

 enum StatusEnum: string
 {
     case ACTIVE = 'active';
     case INACTIVE = 'inactive';
     case AWAITING = 'awaiting';

     public static function toArray(): array
     {
         return array_column(StatusEnum::cases(), 'value');
     }
 }
