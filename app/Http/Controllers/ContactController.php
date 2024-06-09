<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'type' => 'required|string|max:255',
                'contact' => 'required|string|max:255',
            ]);
            $contact = Contact::find($id);
            if (!$contact) {
                throw new \Exception('Contato não encontrado', 404);
            }
            $contact->update([
                'type' => $request->type,
                'contact' => $request->contact,
            ]);

            return response()->json(Response::array(false, 'Contato atualizado com sucesso', ['contact' => $contact]), 200);
        } catch (ValidationException $e) {
            return response()->json(Response::array(true, 'Dados inválidos', $e->errors()), 422);
        } catch (\Exception $e) {
            return response()->json(Response::array(true, 'Erro ao atualizar o contato', ['error' => $e->getMessage()]), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $contact = Contact::find($id);
            if (!$contact) {
                throw new \Exception('Contato não encontrado', 404);
            }
            $person = $contact->person;
            $contact->delete();
            if ($person->contacts()->count() == 0) {
                $person->delete();
            }

            return response()->json(Response::array(false, 'Contato removido com sucesso'), 200);
        } catch (\Exception $e) {
            return response()->json(Response::array(true, 'Erro ao remover o contato', ['error' => $e->getMessage()]), $e->getCode());
        }
    }
}
