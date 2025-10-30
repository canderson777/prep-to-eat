"use client"

import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Search } from "lucide-react"

const categories = ["All", "Breakfast", "Lunch", "Dinner", "Snacks", "Desserts"]

export function RecipeFilters() {
  return (
    <div className="mb-8 space-y-4">
      <div className="relative">
        <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-muted-foreground" />
        <Input type="search" placeholder="Search recipes..." className="pl-10 h-12 text-base" />
      </div>
      <div className="flex flex-wrap gap-2">
        {categories.map((category) => (
          <Button
            key={category}
            variant={category === "All" ? "default" : "outline"}
            size="sm"
            className={category === "All" ? "bg-primary text-primary-foreground hover:bg-primary/90" : ""}
          >
            {category}
          </Button>
        ))}
      </div>
    </div>
  )
}
