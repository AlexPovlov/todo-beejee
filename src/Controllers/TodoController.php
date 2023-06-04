<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Validators\TodoPostValidator;
use App\Validators\TodoUpdateValidator;

class TodoController extends Controller
{
    use JsonWithStatusTrait;
    public function indexAction()
    {
        if (!empty(Request::post())) {

            $validator = new TodoPostValidator();

            if (!$validator->validate(Request::post())) {
                return $this->failure($validator->getErrors(), "", 422);
            }
            $todo = $this->model->create($validator->getData());

            return $this->success($todo);
        }

        $todos = $this->model->get();

        return $this->success($todos);
    }

    public function updateAction()
    {
        if ($todo = $this->model->find($this->route["id"])) {

            if (!empty(Request::post())) {

                $validator = new TodoUpdateValidator();

                if (!$validator->validate(Request::post())) {
                    return $this->failure($validator->getErrors(), "", 422);
                }
                
                $this->model->update($this->route["id"], $validator->getData());
                $todo = $this->model->find($this->route["id"]);

                return $this->success($todo);
            }
        } else {
            return $this->failure([], "", 404);
        }
    }

    public function deleteAction()
    {
        if ($this->model->delete($this->route["id"])) {
            return $this->success();
        } else {
            return $this->failure([], "", 404);
        }
    }

    public function completeAction()
    {
        if ($todo = $this->model->find($this->route["id"])) {

            if ($todo["complete"]) {
                $complete = 0;
            } else {
                $complete = 1;
            }

            $this->model->update($this->route["id"], ["complete" => $complete]);

            return $this->success();
        } else {
            return $this->failure([], "", 404);
        }
    }
}