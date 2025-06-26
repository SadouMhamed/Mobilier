import "./bootstrap";
import React from "react";
import { createRoot } from "react-dom/client";
import LandingPage from "./components/LandingPage";

// Mount React component if the container exists
const container = document.getElementById("react-landing");
if (container) {
    const root = createRoot(container);
    root.render(<LandingPage />);
}
