import { Button } from "@/components/ui/button"
import { ArrowRight, Salad, Clock, Heart } from "lucide-react"
import Link from "next/link"

export function Hero() {
  return (
    <section className="relative overflow-hidden bg-gradient-to-b from-primary/5 to-background py-16 md:py-24 lg:py-32">
      <div className="container px-4 md:px-6">
        <div className="grid gap-8 lg:grid-cols-2 lg:gap-12 items-center">
          <div className="flex flex-col gap-6">
            <div className="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-2 text-sm font-medium text-primary w-fit">
              <Heart className="h-4 w-4" />
              Healthy Living Made Simple
            </div>
            <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight text-foreground text-balance">
              Plan Your Meals, <span className="text-primary">Prep with Ease</span>
            </h1>
            <p className="text-lg md:text-xl text-muted-foreground max-w-xl text-pretty leading-relaxed">
              Discover nutritious recipes, create custom meal plans, and simplify your healthy eating journey with Prep
              to Eat.
            </p>
            <div className="flex flex-col sm:flex-row gap-4">
              <Button size="lg" className="bg-primary text-primary-foreground hover:bg-primary/90 text-base" asChild>
                <Link href="/recipes">
                  Browse Recipes
                  <ArrowRight className="ml-2 h-5 w-5" />
                </Link>
              </Button>
              <Button size="lg" variant="outline" className="text-base bg-transparent" asChild>
                <Link href="/meal-plans">View Meal Plans</Link>
              </Button>
            </div>
            <div className="flex flex-wrap gap-6 pt-4">
              <div className="flex items-center gap-2">
                <div className="flex items-center justify-center w-10 h-10 rounded-lg bg-secondary/20">
                  <Salad className="h-5 w-5 text-secondary" />
                </div>
                <div>
                  <p className="text-sm font-semibold text-foreground">500+ Recipes</p>
                  <p className="text-xs text-muted-foreground">Healthy & Delicious</p>
                </div>
              </div>
              <div className="flex items-center gap-2">
                <div className="flex items-center justify-center w-10 h-10 rounded-lg bg-accent/20">
                  <Clock className="h-5 w-5 text-accent-foreground" />
                </div>
                <div>
                  <p className="text-sm font-semibold text-foreground">Quick Prep</p>
                  <p className="text-xs text-muted-foreground">30 mins or less</p>
                </div>
              </div>
            </div>
          </div>
          <div className="relative">
            <div className="aspect-square rounded-3xl overflow-hidden bg-gradient-to-br from-primary/20 via-secondary/20 to-accent/20 p-8">
              <img
                src="/colorful-healthy-meal-prep-bowls-with-fresh-vegeta.jpg"
                alt="Healthy meal prep bowls"
                className="w-full h-full object-cover rounded-2xl shadow-2xl"
              />
            </div>
            <div className="absolute -bottom-4 -right-4 w-32 h-32 bg-secondary rounded-full blur-3xl opacity-30" />
            <div className="absolute -top-4 -left-4 w-32 h-32 bg-primary rounded-full blur-3xl opacity-30" />
          </div>
        </div>
      </div>
    </section>
  )
}
