import Head from "./components/head";
import React, { useEffect } from "react";
import "@babel/polyfill";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import Main from "./pages/main";
import Login from "./pages/Login";
import { useDispatch, useSelector } from "react-redux";
import Cookies from "js-cookie";
import axios from "axios";
import { delAuth, setAuth } from "./features/userSlice";

const App = () => {
  const user = useSelector((state) => state.user);
  const dispatch = useDispatch();

  useEffect(() => {
    if (Cookies.get("token")) {
      axios
        .get("/api/profile")
        .then((res) => {
          dispatch(setAuth(res.data.data));
        })
        .catch((errors) => {
          dispatch(delAuth());
          Cookies.remove("token");
        });
    }
  }, [dispatch]);

  return (
    <BrowserRouter>
      <Head />
      <Routes>
        <Route path="" element={<Main />} />
        <Route
          path="login"
          element={user.isAuth ? <Navigate to="/" /> : <Login />}
        />
      </Routes>
    </BrowserRouter>
  );
};

export default App;
