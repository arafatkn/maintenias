import ToggleSwitch from './ToggleSwitch';

interface Props {
  maintenanceMode: boolean;
  onToggle: (value: boolean) => void;
  previewUrl?: string;
}

export const Header = ({ maintenanceMode, onToggle, previewUrl }: Props) => {
  return (
    <div className="flex items-center gap-5 bg-gray-200 border-gray-100 rounded-t-lg py-5 px-4">
      <div className="text-xl font-semibold">Maintenance Mode</div>
      <ToggleSwitch checked={maintenanceMode} onChange={onToggle} />
      {maintenanceMode && previewUrl && (
        <a href={previewUrl} target="_blank" rel="noreferrer" className="text-blue-600">
          Preview
        </a>
      )}
    </div>
  );
};
