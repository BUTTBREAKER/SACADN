<?php

namespace SACADN\Enums;

enum HtmlPattern: string {
  case UserAlias = '[a-zA-Z0-9]{4,}';
  case Names = '[a-zA-ZáéíóúÁÉÍÓÚñÑ]{3,20} (\s?[a-zA-ZáéíóúÁÉÍÓÚñÑ]{3,20}){1,3}';
  case LastNames = self::Names;
  case Password = '(?=.*\d)(?=.*[A-Z])(?=.*\W).{8,}';

  function title(): string {
    return match ($this) {
      self::UserAlias => 'Mínimo 4 letras y números',
      self::Names, self::LastNames => 'Mínimo 2 palabras de al menos 3 caracteres',
      self::Password => 'La contraseña debe tener al menos 1 mayúscula, 1 número y un símbolo'
    };
  }
}
