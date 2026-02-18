import { twMerge } from "tailwind-merge";

export interface ToggleSwitchProps {
  checked: boolean;
  onChange?: (value: boolean) => void;
  className?: string;
}

const ToggleSwitch = ({ checked, onChange, className }: ToggleSwitchProps) => {
  return (
    <div
      className={twMerge(
        "relative inline-flex h-6 w-11 items-center rounded-full cursor-pointer transition-colors duration-300",
        checked ? "bg-green-500" : "bg-gray-300",
        className,
      )}
      onClick={() => onChange && onChange(!checked)}
    >
      <span
        className={`${
          checked ? "translate-x-6" : "translate-x-1"
        } inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-300`}
      />
    </div>
  );
};

export default ToggleSwitch;
