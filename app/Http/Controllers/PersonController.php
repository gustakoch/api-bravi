<?php

namespace App\Http\Controllers;

use App\Http\Response;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PersonController extends Controller
{
    public function index()
    {
        try {
            $people = Person::with('contacts')->get();
            if ($people->isEmpty()) {
                throw new \Exception('Nenhuma pessoa encontrada', 404);
            }
            $response = $people->map(function ($person) {
                return [
                    'id' => $person->id,
                    'name' => $person->name,
                    'contacts' => $person->contacts->map(function ($contact) {
                        return [
                            'id' => $contact->id,
                            'type' => $contact->type,
                            'contact' => $contact->contact,
                        ];
                    }),
                ];
            });

            return response()->json(Response::array(false, 'Pessoas encontradas com sucesso', $response->toArray()));
        } catch (\Exception $e) {
            return response()->json(Response::array(true, 'Erro ao buscar pessoas', ['error' => $e->getMessage()]), $e->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:people,name',
                'contacts' => 'required|array|min:1',
                'contacts.*.type' => 'required|string|max:255',
                'contacts.*.contact' => 'required|string|max:255',
            ]);
            $person = Person::create(['name' => $request->name]);
            if (!$person) {
                throw new \Exception('Erro ao criar pessoa', 500);
            }
            foreach ($request->contacts as $contactData) {
                $person->contacts()->create($contactData);
            }
            $response = [
                'id' => $person->id,
                'name' => $person->name,
                'contacts' => $person->contacts->map(function ($contact) {
                    return [
                        'id' => $contact->id,
                        'type' => $contact->type,
                        'contact' => $contact->contact,
                    ];
                })
            ];

            return response()->json(Response::array(false, 'Pessoa criada com sucesso', $response), 201);
        } catch (ValidationException $e) {
            return response()->json(Response::array(true, 'Os dados informados são inválidos', ['errors' => $e->errors()]), 422);
        } catch (\Exception $e) {
            return response()->json(Response::array(true, 'Erro ao criar pessoa', ['error' => $e->getMessage()]), $e->getCode());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:people,name',
            ]);
            $person = Person::find($id);
            if (!$person) {
                throw new \Exception('Pessoa não encontrada', 404);
            }
            $person->update(['name' => $request->name]);

            return response()->json(Response::array(false, 'Pessoa atualizada com sucesso', ['name' => $person->name]));
        } catch (ValidationException $e) {
            return response()->json(Response::array(true, 'Os dados informados são inválidos', ['errors' => $e->errors()]), 422);
        } catch (\Exception $e) {
            return response()->json(Response::array(true, 'Erro ao atualizar pessoa', ['error' => $e->getMessage()]), $e->getCode());
        }
    }

    public function addContact(Request $request, $personId)
    {
        try {
            $request->validate([
                'type' => 'required|string|max:255',
                'contact' => 'required|string|max:255|unique:contacts,contact',
            ]);
            $person = Person::find($personId);
            if (!$person) {
                throw new \Exception('Pessoa não encontrada', 404);
            }
            $contactData = $request->only('type', 'contact');
            $person->contacts()->create($contactData);
            $response = [
                'id' => $person->id,
                'name' => $person->name,
                'contacts' => $person->contacts->map(function ($contact) {
                    return [
                        'id' => $contact->id,
                        'type' => $contact->type,
                        'contact' => $contact->contact,
                    ];
                })
            ];

            return response()->json(Response::array(false, 'Contato adicionado com sucesso', $response));
        } catch (ValidationException $e) {
            return response()->json(Response::array(true, 'Os dados informados são inválidos', ['errors' => $e->errors()]), 422);
        } catch (\Exception $e) {
            return response()->json(Response::array(true, 'Erro ao adicionar contato', ['error' => $e->getMessage()]), $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $person = Person::with('contacts')->find($id);
            if (!$person) {
                throw new \Exception('Pessoa não encontrada', 404);
            }
            $person->contacts()->delete();
            $person->delete();

            return response()->json(Response::array(false, 'Pessoa e contatos excluídos com sucesso'));
        } catch (\Exception $e) {
            return response()->json(Response::array(true, 'Erro ao excluir pessoa e contatos', ['error' => $e->getMessage()]), $e->getCode());
        }
    }
}
