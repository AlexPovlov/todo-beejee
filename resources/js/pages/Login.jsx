import { useDispatch } from "react-redux";
import { setAuth } from "../features/userSlice";
import { useState } from "react";
import axios from "axios";
import { useNavigate } from "react-router-dom";
import Cookies from "js-cookie";

const Login = () => {
  const dispatch = useDispatch();
  const [{ login, password }, setForm] = useState({ login: "", password: "" });
  const [errors, setErrors] = useState({});
  const navigate = useNavigate();

  const auth = (e) => {
    e.preventDefault();
    axios
      .post("/api/login", {
        login: login,
        password: password,
      })
      .then((res) => {
        setErrors({});
        dispatch(setAuth(res.data.data.user));
        Cookies.set("token", res.data.data.token, { expires: 7 });
        setForm({
          login: "",
          password: "",
        });
        navigate("/");
      })
      .catch((error) => {
        setErrors(error.response.data.errors);
      });
  };

  return (
    <div>
      <form>
        <div className="form-group">
          <label>Логин</label>
          <input
            className="form-control"
            placeholder="Введите логин"
            value={login}
            onChange={(e) =>
              setForm({ login: e.target.value, password: password })
            }
          />
          {errors.login?.map((error, i) => (
            <p key={i} className="text-danger">
              {error}
            </p>
          ))}
        </div>
        <div className="form-group">
          <label>Пароль</label>
          <input
            type="password"
            className="form-control"
            placeholder="Введите пароль"
            value={password}
            onChange={(e) =>
              setForm({ password: e.target.value, login: login })
            }
          />
          {errors.password?.map((error, i) => (
            <p key={i} className="text-danger">
              {error}
            </p>
          ))}
        </div>

        <button onClick={auth} className="btn btn-primary">
          Войти
        </button>
      </form>
    </div>
  );
};

export default Login;
