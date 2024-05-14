<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
{

  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
   */
  public function rules(): array
  {
    return [
      'transaction_type' => ['required', Rule::in(config('common.transactionType'))],
      'amount' => [
        'required',
        'decimal:0,2',
        function ($attribute, $value, $fail) {
          if ($this->post('transaction_type') == config('common.transactionType.withdrawal')) {
            $user = Auth::user();
            if ($value > $user->balance) {
              $fail("Amount must be less then total balance");
            }
          }
        }
      ],
    ];
  }
}