<?php

namespace SACADN\Enums;

/** PCRE (Perl-Compatible Regular Expressions) */
enum PCRegExp: string {
  case UserAlias = '/^[a-zA-Z0-9]{4,}$/';
  case Names = '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ]{3,20} (\s?[a-zA-ZáéíóúÁÉÍÓÚñÑ]{3,20}){1,3}$/';
  case Password = '/^(?=.*\d)(?=.*[A-Z])(?=.*\W).{8,}$/';
  case Phone = '/^\+[0-9]{2} [0-9]{3}-[0-9]{7}$/';

  function pattern(): string {
    return mb_substr($this->value, 2, mb_strlen($this->value) - 4);
  }

  function title(): string {
    return match ($this) {
      self::UserAlias => 'Mínimo 4 letras y números',
      self::Names => 'Mínimo 2 palabras de al menos 3 caracteres',
      self::Password => 'La contraseña debe tener al menos 1 mayúscula, 1 número y un símbolo',
      self::Phone => 'Debe estar en notación internacional, ejemplo: +58 000-1112222'
    };
  }
}
