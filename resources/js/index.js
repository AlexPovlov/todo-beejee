import App from "./App";
import ReactDOM from "react-dom";
import { store } from "./store";
import { Provider } from "react-redux";
import React from "react";

ReactDOM.render(
  <React.StrictMode>
    <Provider store={store}>
      <App />
    </Provider>
  </React.StrictMode>,

  document.getElementById("root")
);

const devMode = process.env.NODE_ENV === "development";
if (devMode && module && module.hot) {
  module.hot.accept();
}
