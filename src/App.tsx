/**
 * External dependencies
 */
import { RouterProvider, createHashRouter } from "react-router-dom";

/**
 * Internal dependencies
 */
import { routes } from "./routes";
import { Loader } from "./components/Loader";
import { Header } from "./components/Header";

const App = () => {
  const router = createHashRouter(routes);

  return (
    <div className="mt-5 mx-2 bg-white rounded-lg">
      <Header />
      <div className={"p-4"}>
        <RouterProvider
          router={router}
          fallbackElement={<Loader height="100vh" />}
        />
      </div>
    </div>
  );
};

export default App;
