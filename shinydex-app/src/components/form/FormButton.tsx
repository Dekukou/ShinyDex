interface Props {
  children: React.ReactNode;
  disabled?: boolean;
}

export function FormButton({ children, disabled }: Props) {
  return (
    <button type="submit" disabled={disabled}>
      {children}
    </button>
  );
}
