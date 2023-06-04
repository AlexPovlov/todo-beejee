import { useDispatch } from "react-redux";
import { sortedTodo } from "../features/todoSlice";
import { useState } from "react";

export default function Filter() {
  const dispatch = useDispatch();
  const [direction, setDirection] = useState("asc");
  const [selected, setSelected] = useState("name");

  let class_i = "fa fa text-info btn mx-0 px-0 pl-1";
  let title_i = "";

  if (direction == "asc") {
    class_i += " fa-sort-amount-asc";
    title_i = "Ascending";
  } else {
    class_i += " fa-sort-amount-desc";
    title_i = "Descending";
  }

  const filtered = (e) => {
    setSelected(e.target.value);
    dispatch(sortedTodo({ field: e.target.value, direction: direction }));
  };

  const direct = () => {
    if (direction == "asc") {
      setDirection("desc");
      dispatch(sortedTodo({ field: selected, direction: "desc" }));
    } else {
      setDirection("asc");
      dispatch(sortedTodo({ field: selected, direction: "asc" }));
    }
  };

  return (
    <div className="row m-1 p-3 px-5 justify-content-end">
      <div className="col-auto d-flex align-items-center px-1 pr-3">
        <label className="text-secondary my-2 pr-2 view-opt-label">
          Сортировать
        </label>
        <select
          onChange={filtered}
          defaultValue={selected}
          className="custom-select custom-select-sm btn my-2"
        >
          <option value="name">По имени</option>
          <option value="email">По емайл</option>
          <option value="complete">По статусу</option>
        </select>
        <i
          onClick={direct}
          className={class_i}
          data-toggle="tooltip"
          data-placement="bottom"
          title={title_i}
        ></i>
      </div>
    </div>
  );
}
