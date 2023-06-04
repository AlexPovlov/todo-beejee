import { useState } from "react";
import { delete_todo, setComplete, editTodo } from "../features/todoSlice";
import { useDispatch, useSelector } from "react-redux";
import { delAuth, setAuth } from "../features/userSlice";
import WarnDate from "./WarnDate";
import moment from "moment";
import axios from "axios";

export default function ToDoItem({ item }) {
  const [editing, setEdit] = useState(false);
  const [todo, setTodo] = useState(item);
  const [errors, setErrors] = useState({});
  const user = useSelector((state) => state.user);

  const dispatch = useDispatch();

  let input_class =
    "form-control form-control-lg border-0 edit-todo-input rounded px-3";
  let check_class = "fa text-primary btn m-0 p-0";
  let check_title = "";
  let edit_icon = "fa btn m-0 p-0";

  if (item.complete == 0) {
    check_class += " fa-square-o";
    check_title = "Mark as complete";
  } else {
    check_class += " fa-check-square-o";
    check_title = "Mark as todo";
  }

  const edite = () => {
    if (!user.isAuth) {
      return alert("Вы не авторизованы!");
    }
    if (editing) {
      axios
        .post(`/api/todo/${todo.id}`, todo)
        .then((res) => {
          dispatch(editTodo({ id: todo.id, todo: res.data.data }));
          setErrors({});
          setEdit(false);
        })
        .catch((errors) => {
          if (errors.response.status == 403) {
            dispatch(delAuth());
            return alert(errors.response.data.errors);
          }
          setErrors(errors.response.data.errors);
        });
    } else {
      setEdit(true);
    }
  };

  const complete = () => {
    if (!user.isAuth) {
      return alert("Вы не авторизованы!");
    }
    axios
      .post(`/api/todo/${item.id}/complete`)
      .then(() => {
        item.complete == 0
          ? dispatch(setComplete({ id: todo.id, complete: true }))
          : dispatch(setComplete({ id: todo.id, complete: false }));
      })
      .catch((errors) => {
        if (errors.response.status == 403) {
          dispatch(delAuth());
          alert(errors.response.data.errors);
        }
      });
  };

  if (editing) {
    edit_icon += " fa-check-circle-o text-success";
  } else {
    input_class += " bg-transparent";
    edit_icon += " fa-pencil text-info";
  }

  const delete_t = () => {
    if (!user.isAuth) {
      return alert("Вы не авторизованы!");
    }
    axios
      .post(`/api/todo/${item.id}/delete`)
      .then(() => {
        dispatch(delete_todo(item.id));
      })
      .catch((errors) => {
        if (errors.response.status == 403) {
          dispatch(delAuth());
          alert(errors.response.data.errors);
        }
      });
  };

  return (
    <tr>
      <td>
        <i
          className={check_class}
          data-toggle="tooltip"
          data-placement="bottom"
          title={check_title}
          onClick={complete}
        ></i>
      </td>
      <td>
        {editing ? (
          <input
            type="text"
            className={input_class}
            onChange={(e) => setTodo({ ...todo, name: e.target.value })}
            value={todo.name}
          />
        ) : (
          item.name
        )}
        {errors.name?.map((error) => (
          <p className="text-danger">{error}</p>
        ))}
      </td>
      <td>
        {editing ? (
          <input
            type="text"
            className={input_class}
            onChange={(e) => setTodo({ ...todo, email: e.target.value })}
            value={todo.email}
          />
        ) : (
          item.email
        )}
        {errors.email?.map((error) => (
          <p className="text-danger">{error}</p>
        ))}
      </td>

      <td>
        {editing ? (
          <input
            type="text"
            className={input_class}
            onChange={(e) => setTodo({ ...todo, title: e.target.value })}
            value={todo.title}
          />
        ) : (
          item.title
        )}
        {errors.title?.map((error) => (
          <p className="text-danger">{error}</p>
        ))}
      </td>
      <td>
        <WarnDate>{moment(todo.date).format("DD.MM.YYYY")}</WarnDate>
      </td>
      <td>
        <h5 className="m-0 p-0 px-2">
          <i
            onClick={edite}
            className={edit_icon}
            data-toggle="tooltip"
            data-placement="bottom"
            title="Edit todo"
          ></i>
        </h5>
        <h5 className="m-0 p-0 px-2">
          <i
            className="fa fa-trash-o text-danger btn m-0 p-0"
            data-toggle="tooltip"
            data-placement="bottom"
            title="Delete todo"
            onClick={delete_t}
          ></i>
        </h5>
      </td>
    </tr>
  );
}
