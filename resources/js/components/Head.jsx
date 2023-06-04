import { useDispatch, useSelector } from "react-redux";
import { Link } from "react-router-dom";
import { delAuth } from "../features/userSlice";
import axios from "axios";
import Cookies from "js-cookie";

export default function Head() {
  const user = useSelector((state) => state.user);
  const dispatch = useDispatch();

  const logout = () => {
    axios.post("/api/logout").then(() => {
      dispatch(delAuth());
      Cookies.remove("token");
    });
  };

  return (
    <nav className="navbar navbar-expand-lg navbar-light bg-light">
      <Link className="navbar-brand" to="">
        Задачи
      </Link>
      <button
        className="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarText"
        aria-controls="navbarText"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span className="navbar-toggler-icon"></span>
      </button>
      <div className="collapse navbar-collapse" id="navbarText">
        <ul className="navbar-nav mr-auto"></ul>
        <span className="navbar-text">
          {user.isAuth ? (
            <div>
              <p>{user.data.login}</p>
              <a style={{cursor: 'pointer'}} onClick={logout}>Выйти</a>
            </div>
          ) : (
            <Link  to="login">Вход</Link>
          )}
        </span>
      </div>
    </nav>
  );
}
