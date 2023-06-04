import ToDoItem from "./ToDoItem.jsx";
import { setTodos } from "../features/todoSlice";
import axios from "axios";
import { useSelector, useDispatch } from "react-redux";
import { useEffect, useState } from "react";
import ReactPaginate from "react-paginate";

export default function ToDoList() {
  const dispatch = useDispatch();

  const todos = useSelector((state) => state.todos.value);
  const [itemOffset, setItemOffset] = useState(0);

  const itemsPerPage = 2;
  const endOffset = itemOffset + itemsPerPage;
  const currentItems = todos.slice(itemOffset, endOffset);
  const pageCount = Math.ceil(todos.length / itemsPerPage);

  const handlePageClick = (event) => {
    const newOffset = (event.selected * itemsPerPage) % todos.length;
    setItemOffset(newOffset);
  };

  useEffect(() => {
    const fetchTodosData = async () => {
      try {
        const response = await axios.get("/api/todo");
        dispatch(setTodos(response.data.data));
      } catch (error) {
        console.error("Failed to fetch todos:", error);
      }
    };
    fetchTodosData();
  }, [dispatch]);

  return (
    <div>
      <table className="table table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Имя</th>
            <th>E-mail</th>
            <th>Задача</th>
            <th>Дата</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          {currentItems.map((item, i) => (
            <ToDoItem item={item} index={i} key={item.id} />
          ))}
        </tbody>
      </table>
      <ReactPaginate
        className="pagination justify-content-center"
        activeClassName=" active"
        pageClassName="page-item"
        pageLinkClassName="page-link"
        previousClassName="page-item"
        nextClassName="page-item"
        previousLinkClassName="page-link"
        nextLinkClassName="page-link"
        breakLabel="..."
        nextLabel="next >"
        onPageChange={handlePageClick}
        pageRangeDisplayed={5}
        pageCount={pageCount}
        previousLabel="< previous"
        renderOnZeroPageCount={null}
      />
    </div>
  );
}
