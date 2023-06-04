import { useState } from "react";
import { useDispatch } from "react-redux";
import { add_todo } from "../features/todoSlice";
import ReactDatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.min.css";
import moment from "moment";
import axios from "axios";

export default function From() {
  const [{ title, name, email, date }, setTodo] = useState({
    title: "",
    name: "",
    email: "",
    date: new Date(),
  });

  const [errors, setErrors] = useState({});

  const dispatch = useDispatch();

  const add_todo_click = (e) => {
    e.preventDefault();
    axios
      .post("/api/todo", {
        title: title,
        name: name,
        email: email,
        date: moment(date).format("YYYY-MM-DD"),
      })
      .then((res) => {
        const todo_obj = res.data.data;
        setErrors({});
        dispatch(add_todo(todo_obj));
        setTodo({
          title: "",
          name: "",
          email: "",
          date: new Date(),
        });
      })
      .catch((error) => {
        setErrors(error.response.data.errors);
      });
  };

  return (
    <form>
      <div className="form-group">
        <input
          type="email"
          className="form-control"
          aria-describedby="emailHelp"
          placeholder="E-mail"
          onChange={(e) =>
            setTodo({
              title: title,
              name: name,
              email: e.target.value,
              date: date,
            })
          }
          value={email}
        />
        {errors.email?.map((error) => (
          <p className="text-danger">{error}</p>
        ))}
      </div>
      <div className="form-group">
        <input
          type="text"
          className="form-control"
          id="exampleInputPassword1"
          placeholder="Имя"
          onChange={(e) =>
            setTodo({
              title: title,
              name: e.target.value,
              email: email,
              date: date,
            })
          }
          value={name}
        />
        {errors.name?.map((error) => (
          <p className="text-danger">{error}</p>
        ))}
      </div>
      <div className="form-group">
        <textarea
          className="form-control"
          id="exampleFormControlTextarea1"
          placeholder="Задача"
          rows="3"
          onChange={(e) =>
            setTodo({
              title: e.target.value,
              name: name,
              email: email,
              date: date,
            })
          }
          value={title}
        ></textarea>
        {errors.title?.map((error) => (
          <p className="text-danger">{error}</p>
        ))}
      </div>
      <div className="form-group">
        <ReactDatePicker
          className="form-control"
          selected={date}
          onChange={(date) =>
            setTodo({
              title: title,
              name: name,
              email: email,
              date: date,
            })
          }
        />
      </div>
      <button onClick={add_todo_click} className="btn btn-primary">
        Добавить
        
      </button>
    </form>
  );
}
