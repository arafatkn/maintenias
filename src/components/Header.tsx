import ToggleSwitch from "./ToggleSwitch";
import { useState } from "@wordpress/element";

interface Props {
  //
}

export const Header = ({}: Props) => {
  const [maintenanceMode, setMaintenanceMode] = useState(false);

  return (
    <div className="flex items-center gap-5 bg-gray-200 border-gray-100 rounded-t-lg py-5 px-4">
      <div className="text-xl font-semibold">Maintenance Mode</div>
      <ToggleSwitch checked={maintenanceMode} onChange={setMaintenanceMode} />
      {maintenanceMode && (
        <a href="/" target="_blank">
          Preview
        </a>
      )}
    </div>
  );
};
