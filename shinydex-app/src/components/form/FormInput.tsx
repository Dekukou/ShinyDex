interface Props {
  label: string;
  type?: string;
  value: string;
  onChange: (value: string) => void;
}

export function FormInput({ label, type = 'text', value, onChange }: Props) {
  return (
    <div>
      <label>{label}</label>
      <input
        type={type}
        value={value}
        onChange={(e) => onChange(e.target.value)}
      />
    </div>
  );
}
