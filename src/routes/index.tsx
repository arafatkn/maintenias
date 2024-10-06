import { Index } from "../pages";
import { RouteObject } from "react-router-dom";
import { Settings } from "../pages/settings";

export const routes: RouteObject[] = [
  {
    path: "/",
    element: <Index />,
  },
  {
    path: "/settings",
    element: <Settings />,
  },
];
