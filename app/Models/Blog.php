<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Blog extends Model
{
  use HasFactory;

  protected $fillable = [
    'title', 'description', 'status', 'category_id', 'tag_id', 'user_id'
  ];
}
