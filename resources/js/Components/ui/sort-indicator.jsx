import { ChevronDown, ChevronsUpDown, ChevronUp } from "lucide-react";

export const SortIndicator = ({ label, field, column, direction }) => {
    return (
        <div className="flex items-center">
            <span className="mr-2">{label}</span>
            {field === column ? (
                direction === "asc" ? (
                    <ChevronUp className="h-4 w-4" />
                ) : (
                    <ChevronDown className="h-4 w-4" />
                )
            ) : (
                <ChevronsUpDown className="h-4 w-4" />
            )}
        </div>
    );
};
