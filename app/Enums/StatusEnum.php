<?php
 namespace App\Enums;

 enum StatusEnum: string
 {
     case ACTIVE = '1';
     case INACTIVE = '0';
     case AWAITING = '2';

     public static function toArray(): array
     {
         return array_column(StatusEnum::cases(), 'value');
     }
 }
